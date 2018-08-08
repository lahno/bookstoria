<?php

use SleepingOwl\Admin\Model\ModelConfiguration;
use App\Category;
use App\Collection;
use App\Tag;

AdminSection::registerModel(\App\Book::class, function (ModelConfiguration $model) {
    $model->setTitle('Книги');
    $model->setCreateTitle('Создание книги');


    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync()
            ->setHtmlAttribute('class', 'table-primary')
            ->setDisplaySearch(true)
            ->with('categories','collections')
            ->setColumns([
                AdminColumn::image('cover')->setLabel('Обложка книги'),
                AdminColumn::link('name')->setLabel('Название книги'),
                AdminColumn::text('author_name')->setLabel('Автор'),
                AdminColumn::lists('categories.name')->setLabel('Категории'),
                AdminColumn::lists('collections.name')->setLabel('Тематические подборки'),
                AdminColumn::text('tags')->setLabel('Теги'),
                AdminColumn::datetime('created_at')->setLabel('Дата создания'),
                AdminColumn::datetime('updated_at')->setLabel('Дата обновления')
            ])->paginate(20);

        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {
        return AdminForm::panel()->addBody([
            AdminFormElement::text('name', 'Название книги')->required(),
            AdminFormElement::image('cover', 'Обложка книги')->required(),
            AdminFormElement::selectajax('author_id', 'Владелец')->setModelForOptions(new \App\User())->setDisplay('email'),
            AdminFormElement::text('author_name', 'Автор')->required(),
            AdminFormElement::wysiwyg('annotation', 'Аннотация')->required(),
            AdminFormElement::multiselect('categories', 'Жанры')->setModelForOptions(new Category())->setDisplay('name'),
            AdminFormElement::multiselect('collections', 'Тематические подборки')->setModelForOptions(new Collection())->setDisplay('name'),
            AdminFormElement::text('tags', 'Теги'),
            AdminFormElement::text('booktailer', 'Буктейлер'),
            AdminFormElement::text('price', 'Цена')->required(),
            AdminFormElement::text('chapter_count', 'С какой главы платная(номер главы 0- бесплатная)')->setDefaultValue('0')->required(),
            AdminFormElement::checkbox('translated', 'Это перевод?'),
            AdminFormElement::checkbox('complete', 'Закончена?(если в процессе написания галочку не ставим)'),
            AdminFormElement::checkbox('public', 'Опубликовано')->setDefaultValue('checked'),
        ]);
    });

    // Удаление записи

    // Восстановление записи
    $model->deleting(function(ModelConfiguration $model, \App\Book $book) {

        $book->categories()->detach();
        $book->collections()->detach();

        // Если вернуть false, то удаление будет прервано
    });

    // Установка сообщений
    $model->setMessageOnCreate('Книга создана');
    $model->setMessageOnUpdate('Книга обновлена');
    $model->setMessageOnDelete('Книга удалена');
    $model->setMessageOnRestore('Книга восстановлена');


});