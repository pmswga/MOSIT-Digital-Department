@extends('layout.app')
@section('title', 'Корзина')

@section('content')

    @if($files->isNotEmpty())
        <fieldset class="ui segment">
            <legend><h3>Панель инструментов</h3></legend>
            <div class="ui fluid primary buttons">
                <form class="ui form" style="width: 100%; margin: 0; padding: 0;" method="POST" action="{{ route('storage.restoreAllFromTrash') }}">
                    @csrf
                    <button type="submit" class="ui fluid icon button">
                        <i class="undo icon"></i>
                        Восстановить все файлы
                    </button>
                </form>
            </div>
        </fieldset>
    @endif

    @if($files->isNotEmpty())
        <table class="ui celled striped table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Тег</th>
                    <th>Расширение</th>
                    <th>Размер</th>
                    <th>Дата добавления</th>
                    <th>Дата изменения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            <i class="file icon"></i>
                            {{ $file->getFilename() }}
                        </td>
                        <td>
                            <div class="ui tag label">
                                {{ $file->getFileTag()->getCaption() }}
                            </div>
                        </td>
                        <td>
                            {{ $file->getExtension() }}
                        </td>
                        <td>
                            {{ $file->getSize()." Мб" }}
                        </td>
                        <td>
                            {{ $file->getCreatedDate() }}
                        </td>
                        <td>
                            {{ $file->getUpdatedDate() }}
                        </td>
                        <td>
                            <div class="ui mini basic icon fluid buttons">
                                <a class="ui icon button" href="{{ route('storage.downloadFile', $file) }}">
                                    <i class="download icon"></i>
                                </a>
                                <form style="margin: 0; padding: 0;" method="POST" action="{{ route('storage.restoreFromTrash', $file) }}">
                                    @csrf
                                    <button type="submit" class="ui basic icon fluid button">
                                        <i class="undo icon"></i>
                                    </button>
                                </form>
                                <form style="margin: 0; padding: 0;" method="POST" action="{{ route('storage.destroy', $file) }}" onsubmit="return confirm('Удалить файл?')">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="ui basic icon fluid button">
                                        <i class="red trash icon"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <figure class="ui empty-msg center aligned image">
            <i class="massive trash icon"></i>
            <figcaption>Корзина пуста</figcaption>
        </figure>
    @endif

@endsection