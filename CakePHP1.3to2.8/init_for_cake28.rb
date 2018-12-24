# ruby init_for_cake28.rb app/controllers/users_controller.php

cake13 = ARGV[0]

printf("git checkout -b feature/cake28%s\n", cake13.gsub(/^app\/controllers/,'').gsub(/\//,'_').gsub(/\.php/,''))

cake28 =  'cake28/Controller' + cake13.gsub(/^app\/controllers/,'').gsub(/\.php/,'').split(/\//).map { |v| v.split(/[^[:alnum:]]+/).map(&:capitalize).join }.join('/') + '.php'

printf("git mv %s %s\n", cake13, cake28)
printf("git ci %s %s\n", cake13, cake28)

File.open(cake13) do |f|
  f.each_line do |line|
    if /(sendEmail|LMail::create)/.match(line) then
      puts 'Warnings copy mail template'
      print line
    end
  end
end


cake28_view = cake28.gsub(/^cake28\/Controller/,'cake28/View').gsub(/\.php/,'').gsub(/Controller/,'')
printf("mkdir -p %s\n", cake28_view)

cake13_view = cake13.gsub(/^app\/controllers/,'app/views').gsub(/_controller\.php/,'')
printf("cp %s/* %s\n", cake13_view, cake28_view)
printf("git add %s\n", cake28_view)
printf("git ci %s\n", cake28_view)

puts "Add app/config/Letto/switch.yml"
switch_base = cake13.gsub(/^app\/controllers/,'').gsub(/_controller\.php/,'')
printf("\t- %s\n\t- %s/*\n", switch_base, switch_base)
