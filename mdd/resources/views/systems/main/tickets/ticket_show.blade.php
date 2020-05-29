@extends('layout.app_default')
@section('title', 'Поручение №' . $ticket->idTicket)

@section('content')

    <fieldset class="ui segment">
        <legend><h3>Информация о поручении</h3></legend>
        <table class="ui definition table">
            <col width="20%">
            <tbody>
                <tr>
                    <td>№</td>
                    <td>{{ $ticket->idTicket }}</td>
                </tr>
                <tr>
                    <td>Кем назначено</td>
                    <td>{{ $ticket->getAuthor()->getFullInitials() }}</td>
                </tr>
                <tr>
                    <td>Тип</td>
                    <td>{{ $ticket->getTicketType()->caption }}</td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td>{{ $ticket->getTicketStatus() }}</td>
                </tr>
                <tr>
                    <td>Название</td>
                    <td>{{ $ticket->caption }}</td>
                </tr>
                <tr>
                    <td>Описание</td>
                    <td>{{ $ticket->description }}</td>
                </tr>
                <tr>
                    <td>Дата начала</td>
                    <td>{{ $ticket->getStartDate() }}</td>
                </tr>

                @if($ticket->isExpired())
                    <tr class="error">
                        <td>Дата окончания</td>
                        <td>{{ $ticket->getEndDate() }}</td>
                    </tr>
                @else
                    <tr>
                        <td>Дата окончания</td>
                        <td>{{ $ticket->getEndDate() }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Дата создания</td>
                    <td>{{ $ticket->getCreatedDate() }}</td>
                </tr>
                <tr>
                    <td>Последнее обновление</td>
                    <td>{{ $ticket->getUpdatedDate() }}</td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    <fieldset class="ui segment">
        <legend><h3>Прикреплённые файлы</h3></legend>
        @if($ticket->getAttachedFiles()->count() > 0)
            <div class="ui divided selection list">
                @foreach($ticket->getAttachedFiles() as $file)
                    <a class="item" href="{{ route('tickets.downloadFile', $file) }}">
                        <i class=" file icon"></i>
                        {{ basename($file->path) }}
                    </a>
                @endforeach
            </div>
        @endif
    </fieldset>

    <fieldset class="ui segment">
        <legend><h3>Ответственные лица</h3></legend>
        <table class="ui compact celled table">
            <thead class="full-width">
                <tr>
                    <th>ФИО</th>
                    <th>Должность</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket->getResponsibleEmployees() as $employee)
                    <tr>
                        <td>{{ $employee->getFullInitials() }}</td>
                        <td>{{ $employee->getPost() }}</td>
                    </tr>
                @endforeach
            </tbody>
            {{--
            <tfoot class="full-width">
                <tr>
                    <th colspan="3">
                        <div class="ui right floated small primary labeled icon button">
                            <i class="user icon"></i>
                            Добавить
                        </div>
                    </th>
                </tr>
            </tfoot>
            --}}
        </table>
    </fieldset>

    <fieldset class="ui segment">
        <legend><h3>История поручения</h3></legend>



<div class="ui large feed">
  <div class="event">
    <div class="label">
        <i class="blue plus icon"></i>
    </div>
    <div class="content">
      <div class="summary">
        <a class="user">
            Сергей Головин
        </a>
        создал поручение
        <div class="date">
          01.01.2020 / 10:47
        </div>
      </div>
    </div>
  </div>
  <div class="event">
    <div class="label">
        <i class="comment icon"></i>
    </div>
    <div class="content">
      <div class="summary">
        <a class="user">
          Кирилл Гусев
        </a>
        прокомментировал
        <div class="date">
          01.01.2020 / 12:03
        </div>
      </div>
      <div class="extra text">
        Ох, было бы неплохо
      </div>
    </div>
  </div>

  <div class="event">
    <div class="label">
        <i class="red exclamation icon"></i>
    </div>
    <div class="content">
      <div class="summary">
        Истёк срок выполнения поручения
        <div class="date">
          01.01.2020 / 12:03
        </div>
      </div>
    </div>
  </div>


  <div class="event">
    <div class="label">
        <i class="paperclip icon"></i>
    </div>
    <div class="content">
      <div class="summary">
        <a class="user">
          Евгения Михайлова
        </a>
        прикрепила файлы
        <div class="date">
          01.01.2020 / 13:05
        </div>
      </div>
      <div class="extra text">
        Прикладываю файлы
        <div class="ui selection list">
          <div class="item">
            <i class="file word icon"></i>
            <div class="content">
              <div class="header">Отчёт</div>
            </div>
          </div>
          <div class="item">
            <i class="file excel icon"></i>
            <div class="content">
              <div class="header">Таблицы</div>
            </div>
          </div>
          <div class="item">
            <i class="file powerpoint icon"></i>
            <div class="content">
              <div class="header">Презентация</div>
            </div>
          </div>
          <div class="item">
            <i class="file pdf icon"></i>
            <div class="content">
              <div class="header">Приказ</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="event">
    <div class="label">
        <i class="green check icon"></i>
    </div>
    <div class="content">
      <div class="summary">
        <a class="user">
          Сергей Головин
        </a>
        закрыл поручение
        <div class="date">
          01.01.2020 / 12:03
        </div>
      </div>
    </div>
  </div>
</div>

    </fieldset>


@endsection
