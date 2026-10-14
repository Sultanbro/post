<HTML xmlns="http://www.w3.org/1999/html"><!DOCTYPE html>
   <head>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   </head>
    <p>
        <p>Добрый день!</p>
        <p>Вас пригласили в корпоративный портал mycent.kz</p>
        <p>Чтобы войти под сотрудником компании {{$content['company_name']}} используйте логин: <strong>{{$content['username']}}</strong></p>
        <p>Инструкция по регистрации находится во вложении к настоящему письму.</p>
        <span>Просим пройти регистрацию в личном кабинете </span> <br><br>
        <a style="display: inline-block; padding: 5px 10px; background-color: #b884ff; color: #fff; text-decoration:none; border: 1px solid #ccc; border-radius: 5px; " class="btn btn-primary" href="{{$content['url']}}" role="button">Принять приглашение</a><br><br>

        <b>С уважением, портал mycent.kz</b>
        <!-- JavaScript Bundle with Popper -->
          </body>
</HTML>
