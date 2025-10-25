<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\UserApprovedNotification;
use App\Notifications\UserPendingApprovalNotification;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordResetSuccessNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email=nadim.csm@gmail.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality by sending test emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email functionality...");
        $this->info("Sending test emails to: {$email}");
        
        // Create a test user
        $testUser = new User([
            'name' => 'Test User',
            'email' => $email,
            'phone' => '01712345678',
            'is_approved' => false,
        ]);
        
        try {
            // Test 1: User Pending Approval Notification
            $this->info("Sending User Pending Approval Notification...");
            $testUser->notify(new UserPendingApprovalNotification());
            $this->info("✅ User Pending Approval Notification sent successfully!");
            
            // Test 2: User Approved Notification
            $this->info("Sending User Approved Notification...");
            $testUser->notify(new UserApprovedNotification());
            $this->info("✅ User Approved Notification sent successfully!");
            
            // Test 3: Basic mail test
            $this->info("Sending basic test email...");
            Mail::raw('This is a test email from DC Relief Management System.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - DC Relief Management System');
            });
            $this->info("✅ Basic test email sent successfully!");
            
            // Test 4: Password Reset Notification
            $this->info("Sending Password Reset Notification...");
            $testUser->notify(new PasswordResetNotification('test-token-123'));
            $this->info("✅ Password Reset Notification sent successfully!");
            
            // Test 5: Password Reset Success Notification
            $this->info("Sending Password Reset Success Notification...");
            $testUser->notify(new PasswordResetSuccessNotification());
            $this->info("✅ Password Reset Success Notification sent successfully!");
            
            $this->info("\n🎉 All email tests completed successfully!");
            $this->info("Check your email inbox for the test messages.");
            
        } catch (\Exception $e) {
            $this->error("❌ Email test failed: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}