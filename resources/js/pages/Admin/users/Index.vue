<template>
  <AppLayout title="Users Management">
    <div class="space-y-6 p-6">
      <!-- Enhanced Header with Liquid Glass Effect -->
      <div class="liquidGlass-wrapper rounded-xl no-print">
        <div class="liquidGlass-inner-shine" aria-hidden="true"></div>
        <div class="liquidGlass-content p-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
              <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                Users Management
              </h1>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Manage system users and their permissions
              </p>
              <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="flex items-center">
                  <Users class="w-4 h-4 mr-1" />
                  {{ users.total }} total users
                </span>
                <span class="flex items-center">
                  <UserCheck class="w-4 h-4 mr-1" />
                  {{ activeUsersCount }} active
                </span>
              </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
              <button
                @click="printUsers"
                class="btn-secondary inline-flex items-center justify-center"
              >
                <Printer class="w-4 h-4 mr-2" />
                Print
              </button>
              <button
                @click="exportUsers"
                class="btn-secondary inline-flex items-center justify-center"
              >
                <Download class="w-4 h-4 mr-2" />
                Export
              </button>
              <Link
                :href="route('admin.users.create')"
                class="btn-primary inline-flex items-center justify-center"
              >
                <Plus class="w-4 h-4 mr-2" />
                Add User
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Filters -->
      <div class="card no-print">
        <div class="card-header">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">Filters & Search</h3>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
              <label class="form-label">
                <Search class="inline w-4 h-4 mr-1" />
                Search Users
              </label>
              <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Search by name or email..."
                  class="form-input pl-10"
                  @input="debouncedSearch"
                />
              </div>
            </div>

            <div>
              <label class="form-label">
                <Shield class="inline w-4 h-4 mr-1" />
                Role Filter
              </label>
              <select
                v-model="filters.role"
                class="form-input"
                @change="applyFilters"
              >
                <option value="">All Roles</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="ceo">CEO</option>
                <option value="coo">COO</option>
                <option value="staff">Staff</option>
                <option value="user">User</option>
              </select>
            </div>

            <div>
              <label class="form-label">
                <Activity class="inline w-4 h-4 mr-1" />
                Status Filter
              </label>
              <select
                v-model="filters.status"
                class="form-input"
                @change="applyFilters"
              >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <div class="flex flex-col justify-end space-y-2">
              <button
                @click="clearFilters"
                class="btn-secondary w-full"
              >
                <RotateCcw class="w-4 h-4 mr-2" />
                Clear Filters
              </button>
            </div>
          </div>

          <!-- Active Filters Display -->
          <div v-if="hasActiveFilters" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center gap-2">
              <span class="text-sm text-gray-500 dark:text-gray-400">Active filters:</span>
              <span v-if="filters.search" class="badge badge-info">
                Search: "{{ filters.search }}"
                <button @click="filters.search = ''; applyFilters()" class="ml-1 hover:text-blue-900">×</button>
              </span>
              <span v-if="filters.role" class="badge badge-primary">
                Role: {{ filters.role }}
                <button @click="filters.role = ''; applyFilters()" class="ml-1 hover:text-purple-900">×</button>
              </span>
              <span v-if="filters.status" class="badge badge-warning">
                Status: {{ filters.status }}
                <button @click="filters.status = ''; applyFilters()" class="ml-1 hover:text-yellow-900">×</button>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Users Table -->
      <div class="table-container">
        <div class="table-header">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
              Users List
            </h3>
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
              <span>{{ users.from }}-{{ users.to }} of {{ users.total }}</span>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table print-table">
            <thead class="print-table-header">
              <tr>
                <th class="print:hidden">
                  <input
                    type="checkbox"
                    v-model="selectAll"
                    @change="toggleSelectAll"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                </th>
                <th>
                  <button
                    @click="sortBy('name')"
                    class="group inline-flex items-center space-x-1 text-left font-medium hover:text-gray-900 dark:hover:text-white"
                  >
                    <span>User</span>
                    <ArrowUpDown class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                  </button>
                </th>
                <th>
                  <button
                    @click="sortBy('roles')"
                    class="group inline-flex items-center space-x-1 text-left font-medium hover:text-gray-900 dark:hover:text-white"
                  >
                    <span>Roles</span>
                    <ArrowUpDown class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                  </button>
                </th>
                <th>
                  <button
                    @click="sortBy('status')"
                    class="group inline-flex items-center space-x-1 text-left font-medium hover:text-gray-900 dark:hover:text-white"
                  >
                    <span>Status</span>
                    <ArrowUpDown class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                  </button>
                </th>
                <th>
                  <button
                    @click="sortBy('created_at')"
                    class="group inline-flex items-center space-x-1 text-left font-medium hover:text-gray-900 dark:hover:text-white"
                  >
                    <span>Created</span>
                    <ArrowUpDown class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                  </button>
                </th>
                <th class="print:hidden">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id" class="group">
                <td class="print:hidden">
                  <input
                    type="checkbox"
                    v-model="selectedUsers"
                    :value="user.id"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                </td>
                <td>
                  <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                      <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                        {{ user.name.charAt(0).toUpperCase() }}
                      </div>
                    </div>
                    <div class="min-w-0 flex-1">
                      <div class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ user.name }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                        {{ user.email }}
                      </div>
                      <div v-if="user.staff" class="text-xs text-gray-400 dark:text-gray-500">
                        {{ user.staff.position || 'Staff Member' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="role in user.roles"
                      :key="role.id"
                      :class="getRoleBadgeClass(role.name)"
                      class="badge"
                    >
                      <Shield class="w-3 h-3 mr-1" />
                      {{ role.name }}
                    </span>
                  </div>
                </td>
                <td>
                  <div class="flex items-center space-x-2">
                    <div
                      :class="user.email_verified_at ? 'bg-green-500' : 'bg-red-500'"
                      class="w-2 h-2 rounded-full"
                    ></div>
                    <span
                      :class="user.email_verified_at ? 'badge-success' : 'badge-danger'"
                      class="badge"
                    >
                      {{ user.email_verified_at ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </td>
                <td class="text-sm text-gray-500 dark:text-gray-400">
                  <div>{{ formatDate(user.created_at) }}</div>
                  <div class="text-xs text-gray-400 dark:text-gray-500">
                    {{ formatTimeAgo(user.created_at) }}
                  </div>
                </td>
                <td class="print:hidden">
                  <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <Link
                      :href="route('admin.users.show', user.id)"
                      class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors duration-150"
                      title="View Details"
                    >
                      <Eye class="w-4 h-4" />
                    </Link>
                    <Link
                      :href="route('admin.users.edit', user.id)"
                      class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors duration-150"
                      title="Edit User"
                    >
                      <Edit class="w-4 h-4" />
                    </Link>
                    <button
                      @click="toggleUserStatus(user)"
                      :class="user.email_verified_at ? 'text-orange-600 hover:text-orange-800 hover:bg-orange-50 dark:hover:bg-orange-900/20' : 'text-green-600 hover:text-green-800 hover:bg-green-50 dark:hover:bg-green-900/20'"
                      class="p-2 rounded-lg transition-colors duration-150"
                      :title="user.email_verified_at ? 'Deactivate User' : 'Activate User'"
                    >
                      <UserX v-if="user.email_verified_at" class="w-4 h-4" />
                      <UserCheck v-else class="w-4 h-4" />
                    </button>
                    <button
                      @click="confirmDeleteUser(user)"
                      class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-150"
                      title="Delete User"
                    >
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="users.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center">
                    <Users class="w-12 h-12 text-gray-400 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No users found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                      {{ hasActiveFilters ? 'Try adjusting your filters' : 'Get started by creating your first user' }}
                    </p>
                    <Link
                      v-if="!hasActiveFilters"
                      :href="route('admin.users.create')"
                      class="btn-primary"
                    >
                      <Plus class="w-4 h-4 mr-2" />
                      Add First User
                    </Link>
                    <button
                      v-else
                      @click="clearFilters"
                      class="btn-secondary"
                    >
                      <RotateCcw class="w-4 h-4 mr-2" />
                      Clear Filters
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Enhanced Pagination -->
      <Pagination :data="users" :show-page-size-selector="true" />

      <!-- Bulk Actions (when users are selected) -->
      <div v-if="selectedUsers.length > 0" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-40">
        <div class="liquidGlass-wrapper rounded-full px-6 py-3">
          <div class="liquidGlass-inner-shine" aria-hidden="true"></div>
          <div class="liquidGlass-content flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              {{ selectedUsers.length }} user{{ selectedUsers.length === 1 ? '' : 's' }} selected
            </span>
            <div class="flex items-center space-x-2">
              <button
                @click="bulkActivate"
                class="btn-success btn-sm"
              >
                <UserCheck class="w-4 h-4 mr-1" />
                Activate
              </button>
              <button
                @click="bulkDeactivate"
                class="btn-secondary btn-sm"
              >
                <UserX class="w-4 h-4 mr-1" />
                Deactivate
              </button>
              <button
                @click="bulkDelete"
                class="btn-danger btn-sm"
              >
                <Trash2 class="w-4 h-4 mr-1" />
                Delete
              </button>
              <button
                @click="selectedUsers = []; selectAll = false"
                class="btn-secondary btn-sm"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals and Components -->
    <ConfirmModal ref="confirmModal" />
    <Toast ref="toast" />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
  Plus,
  Search,
  Download,
  Eye,
  Edit,
  Trash2,
  UserCheck,
  UserX,
  ChevronLeft,
  ChevronRight,
  Users,
  Shield,
  Activity,
  RotateCcw,
  ArrowUpDown,
  Printer
} from 'lucide-vue-next'
import ConfirmModal from '@/components/ConfirmModal.vue'
import Toast from '@/components/Toast.vue'
import Pagination from '@/components/Pagination.vue'

interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  roles: Array<{ id: number; name: string }>
}

interface UsersData {
  data: User[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
  prev_page_url: string | null
  next_page_url: string | null
}

const props = defineProps<{
  users: UsersData
}>()

const filters = reactive({
  search: '',
  role: '',
  status: ''
})

// New reactive variables
const selectedUsers = ref<number[]>([])
const selectAll = ref(false)
const sortField = ref('name')
const sortDirection = ref<'asc' | 'desc'>('asc')
const confirmModal = ref<InstanceType<typeof ConfirmModal>>()
const toast = ref<InstanceType<typeof Toast>>()

// Computed properties
const activeUsersCount = computed(() => {
  return props.users.data.filter(user => user.email_verified_at).length
})

const hasActiveFilters = computed(() => {
  return filters.search || filters.role || filters.status
})

const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

function debounce(func: Function, wait: number) {
  let timeout: NodeJS.Timeout
  return function executedFunction(...args: any[]) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

function applyFilters() {
  router.get(route('admin.users.index'), filters, {
    preserveState: true,
    preserveScroll: true
  })
}

function clearFilters() {
  filters.search = ''
  filters.role = ''
  filters.status = ''
  applyFilters()
}

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
  return new Date(dateString).toLocaleDateString()
}

function exportUsers() {
  window.location.href = route('admin.users.export')
}

function toggleUserStatus(user: User) {
  if (confirm(`Are you sure you want to ${user.email_verified_at ? 'deactivate' : 'activate'} this user?`)) {
    router.post(route('admin.users.toggle-status', user.id), {}, {
      preserveScroll: true
    })
  }
}

// Enhanced methods
function toggleSelectAll() {
  if (selectAll.value) {
    selectedUsers.value = props.users.data.map(user => user.id)
  } else {
    selectedUsers.value = []
  }
}

function sortBy(field: string) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }

  // Apply sorting via router
  router.get(route('admin.users.index'), {
    ...filters,
    sort: field,
    direction: sortDirection.value
  }, {
    preserveState: true,
    preserveScroll: true
  })
}

function confirmDeleteUser(user: User) {
  confirmModal.value?.show({
    type: 'danger',
    title: 'Delete User',
    message: `Are you sure you want to delete ${user.name}? This action cannot be undone and will also delete their associated staff profile.`,
    confirmText: 'Delete User',
    cancelText: 'Cancel',
    onConfirm: async () => {
      try {
        await router.delete(route('admin.users.destroy', user.id), {
          preserveScroll: true
        })
        toast.value?.showSuccess('User deleted successfully')
      } catch (error) {
        toast.value?.showError('Failed to delete user', 'Please try again later')
      }
    }
  })
}

function printUsers() {
  window.print()
}

function formatTimeAgo(dateString: string) {
  const date = new Date(dateString)
  const now = new Date()
  const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60))

  if (diffInMinutes < 1) {
    return 'Just now'
  } else if (diffInMinutes < 60) {
    return `${diffInMinutes}m ago`
  } else if (diffInMinutes < 1440) {
    return `${Math.floor(diffInMinutes / 60)}h ago`
  } else {
    return `${Math.floor(diffInMinutes / 1440)}d ago`
  }
}

// Bulk action methods
function bulkActivate() {
  confirmModal.value?.show({
    type: 'success',
    title: 'Activate Users',
    message: `Are you sure you want to activate ${selectedUsers.value.length} user${selectedUsers.value.length === 1 ? '' : 's'}?`,
    confirmText: 'Activate Users',
    cancelText: 'Cancel',
    onConfirm: async () => {
      try {
        await router.post('/admin/users/bulk-activate', {
          user_ids: selectedUsers.value
        }, {
          preserveScroll: true
        })
        selectedUsers.value = []
        selectAll.value = false
        toast.value?.showSuccess('Users activated successfully')
      } catch (error) {
        toast.value?.showError('Failed to activate users', 'Please try again later')
      }
    }
  })
}

function bulkDeactivate() {
  confirmModal.value?.show({
    type: 'warning',
    title: 'Deactivate Users',
    message: `Are you sure you want to deactivate ${selectedUsers.value.length} user${selectedUsers.value.length === 1 ? '' : 's'}?`,
    confirmText: 'Deactivate Users',
    cancelText: 'Cancel',
    onConfirm: async () => {
      try {
        await router.post('/admin/users/bulk-deactivate', {
          user_ids: selectedUsers.value
        }, {
          preserveScroll: true
        })
        selectedUsers.value = []
        selectAll.value = false
        toast.value?.showSuccess('Users deactivated successfully')
      } catch (error) {
        toast.value?.showError('Failed to deactivate users', 'Please try again later')
      }
    }
  })
}

function bulkDelete() {
  confirmModal.value?.show({
    type: 'danger',
    title: 'Delete Users',
    message: `Are you sure you want to delete ${selectedUsers.value.length} user${selectedUsers.value.length === 1 ? '' : 's'}? This action cannot be undone and will also delete their associated staff profiles.`,
    confirmText: 'Delete Users',
    cancelText: 'Cancel',
    onConfirm: async () => {
      try {
        await router.post('/admin/users/bulk-delete', {
          user_ids: selectedUsers.value
        }, {
          preserveScroll: true
        })
        selectedUsers.value = []
        selectAll.value = false
        toast.value?.showSuccess('Users deleted successfully')
      } catch (error) {
        toast.value?.showError('Failed to delete users', 'Please try again later')
      }
    }
  })
}
</script>
