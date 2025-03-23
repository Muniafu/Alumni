<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        {{-- Stats Cards Section --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            {{-- Total Alumni Card --}}
            <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="space-y-1">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Alumni</h3>
                    <p id="totalAlumni" class="text-2xl font-semibold text-neutral-900 dark:text-white">
                        {{ $totalAlumni ?? 0 }}
                    </p>
                </div>
                <div class="mt-4">
                    <x-icon name="users" class="h-8 w-8 text-blue-500" />
                </div>
            </div>
            

            {{-- Recent Graduates Card --}}
            <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="space-y-1">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Recent Graduates</h3>
                    <p id="recentGraduates" class="text-2xl font-semibold text-neutral-900 dark:text-white">
                        {{ $recentGraduates ?? 0 }}
                    </p>
                </div>
                <div class="mt-4">
                    <x-icon name="academic-cap" class="h-8 w-8 text-green-500" />
                </div>
            </div>

            {{-- Active Events Card --}}
            <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="space-y-1">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Active Events</h3>
                    <p id="activeEvents" class="text-2xl font-semibold text-neutral-900 dark:text-white">
                        {{ $activeEvents ?? 0 }}
                    </p>
                </div>
                <div class="mt-4">
                    <x-icon name="calendar" class="h-8 w-8 text-purple-500" />
                </div>
            </div>
        </div>

        {{-- Recent Activity Section --}}
        <div class="flex-1 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-white">Recent Activity</h2>
            <div id="recentActivity">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($recentActivities as $activity)
                            <div class="py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-neutral-100 dark:bg-neutral-700"></div>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $activity->title }}</p>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-500 dark:text-neutral-400">No recent activity</p>
                @endif
            </div>
        </div>
        
    </div>

    {{-- Frontend script to fetch dashboard data from backend --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function fetchDashboardData() {
                fetch('/api/dashboard-data')
                    .then(response => response.json())
                    .then(data => {
                        // Update the stats cards
                        document.getElementById('totalAlumni').innerText = data.totalAlumni ?? 0;
                        document.getElementById('recentGraduates').innerText = data.recentGraduates ?? 0;
                        document.getElementById('activeEvents').innerText = data.activeEvents ?? 0;

                        // Prepare recent activity html
                        const recentActivityContainer = document.getElementById('recentActivity');
                        if (data.recentActivities && data.recentActivities.length > 0) {
                            let html = '<div class="divide-y divide-neutral-200 dark:divide-neutral-700">';
                            data.recentActivities.forEach(activity => {
                                html += `
                                    <div class="py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 rounded-full bg-neutral-100 dark:bg-neutral-700"></div>
                                            <div>
                                                <p class="text-sm font-medium text-neutral-900 dark:text-white">${activity.title}</p>
                                                <p class="text-sm text-neutral-500 dark:text-neutral-400">${activity.diffForHumans}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            html += '</div>';
                            recentActivityContainer.innerHTML = html;
                        } else {
                            recentActivityContainer.innerHTML = `<p class="text-neutral-500 dark:text-neutral-400">No recent activity</p>`;
                        }

                        // Update Upcoming Events
                        const upcomingEventsContainer = document.getElementById('upcomingEvents');
                        if (data.upcomingEvents && data.upcomingEvents.length > 0) {
                            let html = '';
                            data.upcomingEvents.forEach(event => {
                                html += `
                                    <div class="py-4 border-b border-neutral-200 dark:border-neutral-700">
                                        <h4 class="text-md font-semibold text-neutral-900 dark:text-white">${event.title}</h4>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">${event.description}</p>
                                        <p class="text-sm font-medium text-blue-500">${event.startDate}</p>
                                    </div>
                                `;
                            });
                            upcomingEventsContainer.innerHTML = html;
                        } else {
                            upcomingEventsContainer.innerHTML = `<p class="text-neutral-500 dark:text-neutral-400">No upcoming events</p>`;
                
                    })
                    .catch(error => console.error('Error fetching dashboard data:', error));
            }

            // Initial fetch on page load
            fetchDashboardData();

            // Optionally, refresh the dashboard data every minute (60000ms)
            setInterval(fetchDashboardData, 60000);
        });
    </script>
</x-layouts.app>
