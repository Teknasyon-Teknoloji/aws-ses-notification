# docker
Docker for PHP Projects

## Docker Kurulumu

İşletim sistemine uygun docker aşağıdaki adresten kurulabilir:

https://www.docker.com/products/docker

Mac için : https://download.docker.com/mac/beta/Docker.dmg
Windows için : https://download.docker.com/win/beta/InstallDocker.msi

## Docker Machine Çalıştırılması ( Mac OSX )

Docker kurultudktan sonra Docker.app uygulaması çalıştırılır. Sağ üst menüye gelen Dokcer ikonu ile istenen ayarlar yapılır.
Yine bu menuden docker restart yapılabilir veya kapatılabilir.

## Docker Imaj Oluşturulması
Öncelikle docker dosyaları proje ana dizini içerisinde "docker" isimli bir dizin oluşturulup oraya taşınmalı.
Projenin docker imajını hazırlamak için projedeki docker dizini altında aşağıdaki komut çalıştırılabilir.
Imaj bu dizindeki Dockerfile dosyasına göre hazırlanmaktadır. Proje ihtiyaçlarına göre bu dosya gözden geçirilip eksik bir sistem modülü veya php modülü eklenebilir.

```bash
$ docker build -t [PROJECT_IMG_NAME] .
```

Bu komutta [PROJECT_IMG_NAME] ismini projeye uygun olarak değiştirmelisiniz. Örneğin mymobi_img gibi.

## Proje Docker Container Ayarları
Proje için container ayarlayıp çalıştırması docker-compose komutu ile yapılabilir.
Öncelikle "docker-compser.yml" dosyası içerisinde proje için gereken değişiklikler yapılır.
Proje ismi ile docker container ismi belirlenir. İmaj ismi önemlidir. Önceki adımda oluşturulan imajın ismi verilmelidir.
Daha sonra proje ihtiyaçlarına göre port, volume vs. eklenebilir.

Default ayarlar ile geliştirici makinasından proje ana dizini docker container içersine default "/data/project" olarak bağlanacaktır.
Ayrıca nginx, php  gibi ayar dosyalarında geçen "log/", "public/" varsayılan dizinleri ya oluşturulmalı ya da projeye uygun edit edilmelidir.

```bash
$ docker-compose up -d
```
## Container Erişimi
Çalışan containera ssh ile erişmek için

```bash
$ docker exec -it [CONTAINER_NAME] /bin/bash
```

CONTAINER_NAME docker-compose.yml dosyasında servisin container_name ayarı ile tanımlanır. Bu ayar docker exec komutu ile kullanılabilir.

Container'lar çalıştıktan sonra erişim portları otomatik olarak tanımlanır. Bu portlar aşağıdaki komutla görülebilir:
```bash
$ docker ps
```

Web üzerinden erişmek için ayarlanmışsa docker composerda ilişkilendirilen port ile veya "docker ps" komutu ile listenen otomatik port ile aşağıdaki gibi çağrılır.

http://localhost:PORT/

Çalışan sanal makina sizin makinanızı 172.17.0.1 olarak görür. Gereken yerlerde bu ip kullanılabilir.

Docker network şu komut ile kontrol edilebilir:
```bash
$ docker network inspect bridge
```

## Docker Servisleri
Mysql, memcached ve gearmand servisleri öntanımlı olarak eklenmiştir. Proje ihtiyaçlarına göre yeni servisler eklenebilir ya da çıkartılabilir. https://hub.docker.com/ adresi üzerinden resmi ve resmi olmayan servisler ihtiyaçlara göre kullanılabilir.

Uygulama servisine links ayarı ile tanıtılan servislere, ilgili servis adı üzerinden erişilebilir. Örneğin veritabanı bağlantısı ya da memcached erişimi için IP adresi yerine mysql:3306, memcached:11211 portu üzerinden erişim sağlanabilir.

Servislerde kullanılan ports ayarı ise, bu portun ana makinadan erişilebilir olmasını sağlar. Eğer bir servisin ana makinadan erişilmesine ihtiyaç yoksa bu ayar kaldırılabilir. Genel olarak mysql veritabanına erişim gerektiğinden, docker-compose.yml dosyasında bu servisin port ayarı tanımlanmıştır.

## Faydalı Docker Komutları
Çalışan containerları listelemek için
```bash
$ docker ps
```

Tüm containerları listelemek için
```bash
$ docker ps -a
```

Çalışan bir docker container durdurma
```bash
$ docker stop [CONTAINER_NAME]
```

Varolan tüm docker containerları durdurma
```bash
$ docker stop $(docker ps -a -q);
```

Durdurulmuş bir docker container başlatma
```bash
$ docker start [CONTAINER_NAME]
```

Bir docker container silme ( Container öncelikle durmuş olmalı )
```bash
$ docker rm [CONTAINER_NAME]
```

Tüm oluşturulmuş docker containerları silme ( Tüm container öncelikle durmuş olmalı )
```bash
$ docker rm $(docker ps -a -q);
```

Tüm imajları listeleme
```bash
$ docker images
```

Bir docker imajını silme
```bash
$ docker rmi [IMAJ_ID]
```