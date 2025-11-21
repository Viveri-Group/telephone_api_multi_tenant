<?php

Schedule::command('model:prune')->daily();

Schedule::command('viveri:active-call-clear-up')->everyTwoMinutes();

Schedule::command('viveri:process-phone-line-schedule')->everyMinute();
