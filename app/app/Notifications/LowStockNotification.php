<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail']; 
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Alert: Low Product Stock')
            ->line("The stock for the product **{$this->product->name}** has dropped to **{$this->product->stock}** units.")
            ->action('Manage Inventory', url('/admin/products'))
            ->line('Please restock to avoid running out.');
    }
    
}
