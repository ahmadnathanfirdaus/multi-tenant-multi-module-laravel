<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CrmContact;
use App\Models\InventoryProduct;
use App\Models\SupportTicket;
use App\Models\User;

class AnalyticsController extends Controller
{
    public function index()
    {
        $analytics = $this->getAnalyticsData();
        return view('modules.analytics.index', compact('analytics'));
    }

    private function getAnalyticsData()
    {
        $data = [
            'overview' => [
                'total_users' => User::count(),
                'total_blog_posts' => 0,
                'total_contacts' => 0,
                'total_products' => 0,
                'total_tickets' => 0,
            ],
            'recent_activity' => [
                'new_users_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
                'new_posts_this_week' => 0,
                'new_contacts_this_week' => 0,
                'new_products_this_week' => 0,
                'new_tickets_this_week' => 0,
            ],
            'charts' => [
                'users_by_month' => $this->getUsersByMonth(),
                'activity_by_module' => $this->getActivityByModule(),
            ]
        ];

        // Get module-specific data if tables exist
        try {
            $data['overview']['total_blog_posts'] = BlogPost::count();
            $data['recent_activity']['new_posts_this_week'] = BlogPost::where('created_at', '>=', now()->subDays(7))->count();
        } catch (\Exception) {}

        try {
            $data['overview']['total_contacts'] = CrmContact::count();
            $data['recent_activity']['new_contacts_this_week'] = CrmContact::where('created_at', '>=', now()->subDays(7))->count();
        } catch (\Exception) {}

        try {
            $data['overview']['total_products'] = InventoryProduct::count();
            $data['recent_activity']['new_products_this_week'] = InventoryProduct::where('created_at', '>=', now()->subDays(7))->count();
        } catch (\Exception) {}

        try {
            $data['overview']['total_tickets'] = SupportTicket::count();
            $data['recent_activity']['new_tickets_this_week'] = SupportTicket::where('created_at', '>=', now()->subDays(7))->count();
        } catch (\Exception) {}

        return $data;
    }

    private function getUsersByMonth()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'count' => User::whereYear('created_at', $date->year)
                              ->whereMonth('created_at', $date->month)
                              ->count()
            ];
        }
        return $months;
    }

    private function getActivityByModule()
    {
        $activity = [];

        try {
            $activity['Blog'] = BlogPost::where('created_at', '>=', now()->subDays(30))->count();
        } catch (\Exception) {
            $activity['Blog'] = 0;
        }

        try {
            $activity['CRM'] = CrmContact::where('created_at', '>=', now()->subDays(30))->count();
        } catch (\Exception) {
            $activity['CRM'] = 0;
        }

        try {
            $activity['Inventory'] = InventoryProduct::where('created_at', '>=', now()->subDays(30))->count();
        } catch (\Exception) {
            $activity['Inventory'] = 0;
        }

        try {
            $activity['Support'] = SupportTicket::where('created_at', '>=', now()->subDays(30))->count();
        } catch (\Exception) {
            $activity['Support'] = 0;
        }

        return $activity;
    }
}
