<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowedBook;

class UpdateOverdueBooks extends Command
{
    protected $signature = 'books:update-overdue';

    protected $description = 'Update status of overdue books';

    public function handle()
    {
        $overdueBooks = BorrowedBook::whereDate('return_date', '<', now())
            ->where('returned', false)
            ->get();

        foreach ($overdueBooks as $book) {
            $book->update(['returned' => true]);
        }

        $this->info('Overdue books status updated');
    }
}