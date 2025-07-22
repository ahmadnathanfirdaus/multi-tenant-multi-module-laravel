<x-layouts.app>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar mr-3 text-purple-500"></i>
                Analytics Dashboard
            </h1>
            <p class="text-gray-600 mt-2">Overview of your tenant's data and activity</p>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['overview']['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-blog text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Blog Posts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['overview']['total_blog_posts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-address-book text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Contacts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['overview']['total_contacts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <i class="fas fa-boxes text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Products</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['overview']['total_products'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-ticket-alt text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tickets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['overview']['total_tickets'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">This Week's Activity</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">New Users</span>
                        <span class="text-sm font-medium text-gray-900">{{ $analytics['recent_activity']['new_users_this_week'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">New Blog Posts</span>
                        <span class="text-sm font-medium text-gray-900">{{ $analytics['recent_activity']['new_posts_this_week'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">New Contacts</span>
                        <span class="text-sm font-medium text-gray-900">{{ $analytics['recent_activity']['new_contacts_this_week'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">New Products</span>
                        <span class="text-sm font-medium text-gray-900">{{ $analytics['recent_activity']['new_products_this_week'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">New Tickets</span>
                        <span class="text-sm font-medium text-gray-900">{{ $analytics['recent_activity']['new_tickets_this_week'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Module Activity (Last 30 Days)</h3>
                <div class="space-y-4">
                    @foreach($analytics['charts']['activity_by_module'] as $module => $count)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $module }}</span>
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $count > 0 ? min(($count / max(array_values($analytics['charts']['activity_by_module']))) * 100, 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Growth (Last 12 Months)</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($analytics['charts']['users_by_month'] as $month)
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 rounded-t" style="height: {{ $month['count'] > 0 ? max($month['count'] * 20, 4) : 4 }}px; width: 24px;"></div>
                        <span class="text-xs text-gray-600 mt-2 transform -rotate-45 origin-top-left">{{ $month['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
