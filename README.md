Для разворачивания нужна будет ubuntu 16.04. Можно развернуть на виртуалке. Вот образ. На ubuntu нужно настроить выход в интернет (network adapter – NAT).

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
<pre>
sudo apt-get install npm
sudo apt-get install nodejs
sudo npm install -g @angular/cli
</pre>
Перейти в директорию с клиентом и скомпилить клиент
<pre>
cd www/stud-account/client
ng build --prod
</pre>

