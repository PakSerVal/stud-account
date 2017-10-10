Для разворачивания нужна будет ubuntu. Можно развернуть на виртуалке. Вот 
<a href="http://releases.ubuntu.com/16.04/ubuntu-16.04.3-desktop-amd64.iso">образ</a>. На ubuntu нужно настроить выход в интернет (network adapter – NAT).

<h1>Установка nginx, php-fpm, php7.0, python, sqlite3</h1>
Заходим в систему, открываем терминал ctrl+alt+t и вводим комманды
<pre>
sudo apt-add-repository ppa:nginx/stable
sudo apt update
sudo apt install nginx
sudo apt-get install software-properties-common python-software-properties
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt-get install php7.0-cli php7.0-common php7.0-mysql php7.0-fpm php-pear
sudo apt-get install php7.0-sqlite3
</pre>

<h1>Установка cubes и cubesviewer</h1> 
Создаем директорию www и переходим туда
<pre>
mkdir www && cd www
sudo apt-get install git
git clone https://github.com/pikachuprogrammer/stud-account.git
sudo apt install python-pip
pip install pytz python-dateutil jsonschema
pip install sqlalchemy flask
pip install cubes
git clone https://github.com/jjmontesl/cubesviewer-server.git
</pre>
Запуск куба и cubesviewer
Открыть файл cubesviewer/cvapp/cvapp/settings.py
<pre>
cd cubesviewer-server/cvapp/cvapp/ && sudo gedit settings.py
</pre>
Заменить строку: 'NAME': os.path.join(BASE_DIR, 'cubesviewer.sqlite') на
'NAME': '/home/sergey/www/stud-account/server/students' (sergey заменить на имя пользователя)

Перейти обратно в директорию CoubesViewer и установить зависимости
<pre>
cd ../.. && pip install -r requirements.txt
</pre>

Перейти в cvapp и мигрировать базу
<pre>cd cvapp && python manage.py migrate</pre>
Создать пользователя. Здесь нужно будет указать логин, почту и пароль
<pre>python manage.py createsuperuser</pre>
Запускаем вьювер
<pre>python manage.py runserver 0.0.0.0:8000</pre>
Открыть терминал в новом окне. Этот не закрывать.
<pre>
cd www/stud-account/sercer
slicer serve slicer.ini
</pre>

Открываем браузер, вводим http://localhost:8000, входим по логину и паролю джанго. 


<h1>Установка клиента программы</h1>
Установка npm, nodem angular-cli
<pre>
cd www/stud-account/client
sudo apt-get install npm
sudo apt-get install nodejs
sudo ln -s /usr/bin/nodejs /usr/bin/node
sudo npm install -g @angular/cli
npm install
sudo npm cache clean -f
sudo npm install -g n
sudo n 6.9.0
</pre>
Скомпилировать клиент 
<pre>
ng build -prod
</pre>
Перейти в директорию сервера и установить пакеты  
<pre>
sudo apt install composer
sudo apt-get install php7.0-xml
cd ../server && composer install
</pre>
Настроить nginx
открыть файл hosts 
<pre>sudo gedit /etc/hosts</pre>
и добавить
<pre>
127.0.0.1	sa.ru
</pre>
скопировать конфиг nginx 
<pre>sudo cp ../sa.conf /etc/nginx/sites-available</pre>
открыть файл конфига
<pre>sudo gedit /etc/nginx/sites-available/sa.conf </pre>
изменить пути "/home/sergo/www/stud-account/server". Вместо моего имени (sergey) ввести имя пользователя
сделать симлинк и перезагрузить nginx
<pre>
sudo ln -s /etc/nginx/sites-available/sa.conf /etc/nginx/sites-enabled/
sudo service nginx restart
</pre>
Заёти в браузере по sa.ru



