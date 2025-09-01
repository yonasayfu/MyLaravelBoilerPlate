<template>
  <AppLayout :title="`User: ${user.name}`">
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <Link
              :href="route('admin.users.index')"
              class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
            >
              <ArrowLeft class="w-5 h-5" />
            </Link>
            <div>
              <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ user.name }}</h1>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                User details and information
              </p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <Link
              :href="route('admin.users.edit', user.id)"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <Edit class="w-4 h-4 mr-2" />
              Edit User
            </Link>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Basic Information -->
          <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ user.name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ user.email }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                <span
                  :class="user.email_verified_at ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                  class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ user.email_verified_at ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(user.created_at) }}</p>
              </div>
              <div v-if="user.email_verified_at">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(user.email_verified_at) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(user.updated_at) }}</p>
              </div>
            </div>
          </div>

          <!-- Staff Information -->
          <div v-if="user.staff" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Staff Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Position</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ user.staff.position || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Department</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ user.staff.department || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{ user.staff.hired_at ? formatDate(user.staff.hired_at) : 'Not specified' }}
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Staff Status</label>
                <span
                  :class="user.staff.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                  class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ user.staff.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Activity Log -->
          <div v-if="activityLog && activityLog.length > 0" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h2>
            <div class="flow-root">
              <ul class="-mb-8">
                <li v-for="(activity, index) in activityLog" :key="activity.id" class="relative pb-8">
                  <div v-if="index !== activityLog.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                  <div class="relative flex space-x-3">
                    <div>
                      <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                        <Activity class="w-4 h-4 text-white" />
                      </span>
                    </div>
                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                      <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                          {{ activity.description }}
                        </p>
                      </div>
                      <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                        {{ formatDate(activity.created_at) }}
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- User Avatar -->
          <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <div class="mx-auto h-24 w-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold mb-4">
              {{ user.name.charAt(0).toUpperCase() }}
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ user.name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
          </div>

          <!-- Roles & Permissions -->
          <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Roles & Permissions</h3>
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Roles</label>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="role in user.roles"
                    :key="role.id"
                    :class="getRoleBadgeClass(role.name)"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  >
                    {{ role.name }}
                  </span>
                </div>
              </div>
              <div v-if="user.permissions && user.permissions.length > 0">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Direct Permissions</label>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="permission in user.permissions"
                    :key="permission.id"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200"
                  >
                    {{ permission.name }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
              <button
                @click="sendMessage"
                class="w-full flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                <MessageSquare class="w-4 h-4 mr-2" />
                Send Message
              </button>
              <button
                @click="toggleUserStatus"
                :class="user.email_verified_at ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'"
                class="w-full flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                <UserX v-if="user.email_verified_at" class="w-4 h-4 mr-2" />
                <UserCheck v-else class="w-4 h-4 mr-2" />
                {{ user.email_verified_at ? 'Deactivate User' : 'Activate User' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
  ArrowLeft,
  Edit,
  Activity,
  MessageSquare,
  UserCheck,
  UserX
} from 'lucide-vue-next'

interface Role {
  id: number
  name: string
}

interface Permission {
  id: number
  name: string
}

interface Staff {
  position: string
  department: string
  hired_at: string
  is_active: boolean
}

interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  updated_at: string
  roles: Role[]
  permissions?: Permission[]
  staff?: Staff
}

interface ActivityLogItem {
  id: number
  description: string
  created_at: string
}

const props = defineProps<{
  user: User
  activityLog?: ActivityLogItem[]
}>()

function getRoleBadgeClass(roleName: string) {
  const classes = {
    superadmin: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    admin: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    ceo: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    coo: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
    staff: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    user: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
  }
  return classes[roleName as keyof typeof classes] || classes.user
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

function sendMessage() {
  // Navigate to messages with this user
  router.visit(`/messages?user=${props.user.id}`)
}

function toggleUserStatus() {
  if (confirm(`Are you sure you want to ${props.user.email_verified_at ? 'deactivate' : 'activate'} this user?`)) {
    router.post(route('admin.users.toggle-status', props.user.id), {}, {
      preserveScroll: true
    })
  }
}
</script>
