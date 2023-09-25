# Проект «Вычислитель отличий»

[![Actions Status](https://github.com/StenidoS/php-project-differ/workflows/hexlet-check/badge.svg)](https://github.com/StenidoS/php-project-differ/actions)
[![Github Actions Status](https://github.com/StenidoS/php-project-differ/workflows/CI/badge.svg)](https://github.com/StenidoS/php-project-differ/actions)
[![Maintainability]()](https://github.com/StenidoS/php-project-differ/maintainability)
[![Test Coverage]()](https://github.com/StenidoS/php-project-differ/test_coverage)

Вычислитель отличий – программа, определяющая разницу между двумя структурами данных. Это популярная задача, для решения 
которой существует множество онлайн-сервисов. Подобный механизм используется при выводе тестов или при автоматическом 
отслеживании изменении в конфигурационных файлах.

Возможности утилиты:

* Поддержка разных входных форматов: yaml и json
* Генерация отчета в виде plain text, stylish и json

## Установка

```bash
git clone https://github.com/StenidoS/php-project-differ.git differ
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

[![asciicast]()]()

### Сравнение плоских файлов yaml. Вывод результата в формате stylish

[![asciicast]()]()

### Рекурсивное сравнение Json и Yaml. Вывод результата в формате stylish

[![asciicast]()]()

### Рекурсивное сравнение Json. Вывод результата в формате plain

[![asciicast]()]()

### Рекурсивное сравнение Json. Вывод результата в формате json

[![asciicast]()]()
