# Gulp + WordPress

Version: 2.0.8

# WordPress Starter Theme

## Опис

Цей проект є стартовим темплейтом для розробки тем WordPress. Він включає в себе налаштування Gulp для автоматизації задач, таких як компіляція SCSS, мінімізація JavaScript та інші корисні функції.

## Встановлення

1. Клонуйте проект у директорію `wp-content/themes` та перейменуйте його відповідно.
2. Оновіть файл `style.css` у корені теми з усією відповідною інформацією.
3. Оновіть `assets/package.json` (зокрема `name` та `author`).
4. Оновіть `assets/gulpfile.mjs` (змініть змінну `themePrefix` відповідно).

### Встановлення Gulp

Якщо ви ще не встановили Gulp, вам потрібно [зробити це](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md) спочатку.

```sh
npm install
```

Після встановлення пакетів, у терміналі, перебуваючи в директорії assets, запустіть:

```sh
gulp watch
```
Для початкової збірки активів ви можете запустити стандартне завдання:

```sh
gulp
``` 
## Функції
### Gulp
Gulp налаштований для виконання наступних завдань:  
* Компіляція та мінімізація SCSS файлів у `assets/scss/` до `assets/css`.
* Мінімізація та конкатенація JavaScript файлів у `assets/js/scripts/` до `assets/js/`.

### WordPress
У директорії `functions` знаходяться корисні функції для WordPress, включаючи:  
* Зміна кредиту адміністратора у футері.
* Зміна стандартного привітання "Howdy".
* Підключення скриптів та стилів.
* Інструмент для відладки PHP.
* Реєстрація користувацького меню навігації.

### Використані пакети
Проект використовує наступні пакети Gulp:
* `gulp`
* `gulp-shell`
* `gulp-cache`
* `gulp-dart-sass`
* `gulp-autoprefixer`
* `gulp-clean-css`
* `gulp-concat`
* `gulp-rename`
* `gulp-terser`
* `gulp-sourcemaps`
* `gulp-connect-php`
* `browser-sync`
* `gulp-plumber`


### Файлова структура
```sh
my-wordpress-theme/
├── assets/
│   ├── css/                # Згенеровані CSS файли
│   ├── js/                 # JavaScript файли
│   │   ├── libs/           # Бібліотеки JavaScript
│   │   └── scripts/        # Користувацькі скрипти
│   ├── scss/               # SCSS файли для стилів
│   ├── gulpfile.mjs        # Gulp конфігураційний файл
│   └── package.json        # NPM конфігураційний файл
├── functions/
│   ├── func-script.php     # Функції для підключення скриптів
│   └── other-functions.php # Інші корисні функції
├── templates/
│   ├── knowledge.php       # Шаблон сторінки Knowledge
│   └── journal.php         # Шаблон сторінки Journal
├── .gitignore              # Файл для ігнорування файлів Git
├── README.md               # Документація проекту
├── footer.php              # Шаблон футера
├── header.php              # Шаблон хедера
├── index.php               # Головний шаблон теми
├── style.css               # Головний файл стилів теми
└── functions.php           # Головний файл функцій теми

```

## Кредити

- [WordPress](https://wordpress.com)
- [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards)
- [Gulp.js](http://gulpjs.com/)
- [SASS / SCSS](http://sass-lang.com/)

## Ліцензія

Цей проект ліцензований під [MIT License](LICENSE).
