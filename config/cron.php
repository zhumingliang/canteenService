<?php

return [
    'tasks' => [
        \app\task\clearOrder::class,
        \app\task\clearAccount::class,
        \app\task\clearAccount2::class,
        \app\task\sendClearMsg::class,
        \app\task\checkMachine::class,
        \app\task\checkMachine2::class,
        \app\task\checkMachine3::class,
    ]
];