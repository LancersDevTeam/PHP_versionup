#!/bin/env ruby
# coding: utf-8

branch    = ARGV[0]
username  = ARGV[1]
build_url = ARGV[2]

root_path = Dir::pwd
target_list_file_path = '/tmp/diff.log'

results = Hash.new

def update_hash(results,file,key,line,line_num)
  results[file] = Hash.new() unless results.key?(file)
  results[file][key] = [] unless results[file].key?(key)
  results[file][key] << "#{line_num}"
end

File.open(target_list_file_path) do |target_list_file|
  target_list_file.each_line do |file|
    file.chomp!
    next unless File.exist?(file)
    next unless /^cake28\/(?:Controller|View).+\.(?:php|ctp)$/.match(file)
    next if /^cake28\/Controller\/Component\/QdmailComponent\.php$/.match(file)
    next if /^cake28\/Controller\/Component\/PermissionComponent\.php$/.match(file)
    next if /^cake28\/Controller\/Component\/RecommendUserComponent\.php$/.match(file)
    next if /^cake28\/Controller\/Component\/RecommendWorkComponent\.php$/.match(file)
    next if /^cake28\/View\/Helper\/Compatible13PaginatorHelper\.php$/.match(file)
    File.open(file) do |f|
      line_num = 1
      f.each_line do |line|
        line.chomp!
        # class名とファイル名が一致するかチェック
        if /^class *(.+?(?:Behavior|Component|Controller))/.match(line) then
          controller_name = $1
          if !file.include?(controller_name) then
            update_hash(results,file,controller_name,line,line_num)
          end
        # $this->params系チェック
        elsif /(this->(here|header|webroot|data[^a-zA-Z]|params|action[^a-zA-Z]|RequestHandler|params\['form'\]))/.match(line) ||
          /(cakeError)/.match(line) ||
          /(this->Auth->allow\((array\('\*'\)\)|\('\*'\)))/.match(line) then
          update_hash(results,file,$1,line,line_num)
        # Cacheキーチェック
        elsif /(Cache::(?:read|write)\(\s*'(.*?)')/.match(line) then
          target    = $1
          cache_key = $2
          if /[A-Z]/.match(cache_key) then
            update_hash(results,file,target,line,line_num)
          end
        # $this->set系チェック
        elsif /^cake28\/View.+$/.match(file) &&
          /(this->set)/.match(line) then
          update_hash(results,file,$1,line,line_num)
        # $this-render系チェック
        elsif /this->render\(["'](.*?)["']\)/.match(line) then
          render_path = $1
          if /^\/?[a-z].*?\//.match(render_path) then
            update_hash(results,file,render_path,line,line_num)
          end
        # $this->elementの大文字始まりチェック
        elsif /this->element\(["'](.*?)["']/.match(line) then
          render_path = $1
          if render_path.start_with?('Emails') || render_path.start_with?('/Emails') then
            # Emailsだけは大文字始まりで正しいのでチェック不要
          elsif /^\/?[A-Z].*?\//.match(render_path) then
            update_hash(results,file,render_path,line,line_num)
          end
        end
        line_num += 1
      end
    end
  end
end

exit!(0) if results.size == 0

cakephp28_log = '/tmp/cakephp28.log'

File.open(cakephp28_log,'w') do |f|
  results.each do |file,value|
    value.each do |match,num_lines|
      num_lines.each do |num_line|
        f.puts(sprintf("%s\t%s\t%s",file, match, num_line))
      end
    end
  end
end

system("cat #{cakephp28_log}")
system("sh #{root_path}/cake28/Test/CodingChecker/send_messages.sh 'cifailed' #{branch} #{username} #{build_url} #{cakephp28_log}")

exit!
