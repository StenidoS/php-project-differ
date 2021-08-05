# Проект «Вычислитель отличий»

[![Actions Status](https://github.com/bysynth/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/bysynth/php-project-lvl2/actions)
[![Github Actions Status](https://github.com/bysynth/php-project-lvl2/workflows/CI/badge.svg)](https://github.com/bysynth/php-project-lvl2/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/9f83efce639667fe4221/maintainability)](https://codeclimate.com/github/bysynth/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/9f83efce639667fe4221/test_coverage)](https://codeclimate.com/github/bysynth/php-project-lvl2/test_coverage)

Вычислитель отличий – программа, определяющая разницу между двумя структурами данных. Это популярная задача, для решения 
которой существует множество онлайн-сервисов. Подобный механизм используется при выводе тестов или при автоматическом 
отслеживании изменении в конфигурационных файлах.

Возможности утилиты:

* Поддержка разных входных форматов: yaml и json
* Генерация отчета в виде plain text, stylish и json

## Установка

```bash
git clone https://github.com/bysynth/php-project-lvl2.git differ
cd differ
make install
```

## Инструкция по использованию:

Инструкция по использованию доступна по команде ```./bin/gendiff -h```.

Варианты форматирования результата работы программы:

- ```stylish```
- ```plain```
- ```json```

### Сравнение плоских файлов json. Вывод результата в формате stylish

[![asciicast](https://asciinema.org/a/CJvOblN803ISLO5dkh8Z4QSXP.svg)](https://asciinema.org/a/CJvOblN803ISLO5dkh8Z4QSXP)

### Сравнение плоских файлов yaml. Вывод результата в формате stylish

[![asciicast](https://asciinema.org/a/mLLIyNtzbRNT15eO9bojV5tng.svg)](https://asciinema.org/a/mLLIyNtzbRNT15eO9bojV5tng)

### Рекурсивное сравнение Json и Yaml. Вывод результата в формате stylish

[![asciicast](https://asciinema.org/a/Qeke1CPw2NpBXYsrFWZcclsZf.svg)](https://asciinema.org/a/Qeke1CPw2NpBXYsrFWZcclsZf)

### Рекурсивное сравнение Json. Вывод результата в формате plain

[![asciicast](https://asciinema.org/a/GD1t8lWV31tJ5A1d9UMrToUul.svg)](https://asciinema.org/a/GD1t8lWV31tJ5A1d9UMrToUul)

### Рекурсивное сравнение Json. Вывод результата в формате json

[![asciicast](https://asciinema.org/a/BZ8d7heHYOKybdWUskQ4ujj1E.svg)](https://asciinema.org/a/BZ8d7heHYOKybdWUskQ4ujj1E)