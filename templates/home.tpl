<h1>Smarty Blog</h1>
<p>Приложение запущено. PHP {$phpVersion|escape}.</p>
{if $dbConnected !== null}
<p>{if $dbConnected}База данных подключена.{else}База данных недоступна.{/if}</p>
{/if}
