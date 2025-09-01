<template>
  <div v-if="data.last_page > 1" class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:px-6">
    <!-- Mobile pagination -->
    <div class="flex flex-1 justify-between sm:hidden">
      <Link
        v-if="data.prev_page_url"
        :href="data.prev_page_url"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
      >
        Previous
      </Link>
      <span v-else class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md cursor-not-allowed">
        Previous
      </span>
      
      <Link
        v-if="data.next_page_url"
        :href="data.next_page_url"
        class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
      >
        Next
      </Link>
      <span v-else class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md cursor-not-allowed">
        Next
      </span>
    </div>

    <!-- Desktop pagination -->
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <!-- Results info -->
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-300">
          Showing
          <span class="font-medium">{{ data.from || 0 }}</span>
          to
          <span class="font-medium">{{ data.to || 0 }}</span>
          of
          <span class="font-medium">{{ data.total || 0 }}</span>
          results
        </p>
      </div>

      <!-- Pagination controls -->
      <div>
        <nav class="pagination" aria-label="Pagination">
          <!-- Previous button -->
          <Link
            v-if="data.prev_page_url"
            :href="data.prev_page_url"
            class="pagination-item"
            aria-label="Previous page"
          >
            <ChevronLeft class="h-4 w-4" />
          </Link>
          <span v-else class="pagination-item opacity-50 cursor-not-allowed" aria-label="Previous page">
            <ChevronLeft class="h-4 w-4" />
          </span>

          <!-- Page numbers -->
          <template v-for="page in visiblePages" :key="page">
            <Link
              v-if="page !== '...'"
              :href="getPageUrl(page)"
              class="pagination-item"
              :class="{ 'active': page === data.current_page }"
              :aria-label="`Go to page ${page}`"
              :aria-current="page === data.current_page ? 'page' : undefined"
            >
              {{ page }}
            </Link>
            <span v-else class="pagination-item cursor-default">
              {{ page }}
            </span>
          </template>

          <!-- Next button -->
          <Link
            v-if="data.next_page_url"
            :href="data.next_page_url"
            class="pagination-item"
            aria-label="Next page"
          >
            <ChevronRight class="h-4 w-4" />
          </Link>
          <span v-else class="pagination-item opacity-50 cursor-not-allowed" aria-label="Next page">
            <ChevronRight class="h-4 w-4" />
          </span>
        </nav>
      </div>
    </div>

    <!-- Page size selector -->
    <div v-if="showPageSizeSelector" class="hidden lg:flex lg:items-center lg:space-x-2 lg:ml-4">
      <label for="page-size" class="text-sm text-gray-700 dark:text-gray-300">
        Show:
      </label>
      <select
        id="page-size"
        :value="data.per_page"
        @change="handlePageSizeChange"
        class="form-input text-sm py-1 px-2 w-20"
      >
        <option v-for="size in pageSizeOptions" :key="size" :value="size">
          {{ size }}
        </option>
      </select>
      <span class="text-sm text-gray-700 dark:text-gray-300">per page</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

interface PaginationData {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number | null
  to: number | null
  prev_page_url: string | null
  next_page_url: string | null
  path: string
}

interface Props {
  data: PaginationData
  showPageSizeSelector?: boolean
  pageSizeOptions?: number[]
  maxVisiblePages?: number
}

const props = withDefaults(defineProps<Props>(), {
  showPageSizeSelector: false,
  pageSizeOptions: () => [10, 25, 50, 100],
  maxVisiblePages: 7
})

const visiblePages = computed(() => {
  const current = props.data.current_page
  const last = props.data.last_page
  const max = props.maxVisiblePages
  
  if (last <= max) {
    return Array.from({ length: last }, (_, i) => i + 1)
  }
  
  const pages: (number | string)[] = []
  const half = Math.floor(max / 2)
  
  // Always show first page
  pages.push(1)
  
  let start = Math.max(2, current - half)
  let end = Math.min(last - 1, current + half)
  
  // Adjust if we're near the beginning
  if (current <= half + 1) {
    end = Math.min(last - 1, max - 1)
  }
  
  // Adjust if we're near the end
  if (current >= last - half) {
    start = Math.max(2, last - max + 2)
  }
  
  // Add ellipsis after first page if needed
  if (start > 2) {
    pages.push('...')
  }
  
  // Add middle pages
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  // Add ellipsis before last page if needed
  if (end < last - 1) {
    pages.push('...')
  }
  
  // Always show last page (if it's not the first page)
  if (last > 1) {
    pages.push(last)
  }
  
  return pages
})

const getPageUrl = (page: number | string) => {
  if (typeof page === 'string') return '#'
  
  const url = new URL(props.data.path, window.location.origin)
  const params = new URLSearchParams(window.location.search)
  
  if (page === 1) {
    params.delete('page')
  } else {
    params.set('page', page.toString())
  }
  
  const queryString = params.toString()
  return queryString ? `${url.pathname}?${queryString}` : url.pathname
}

const handlePageSizeChange = (event: Event) => {
  const target = event.target as HTMLSelectElement
  const perPage = parseInt(target.value)
  
  const url = new URL(window.location.href)
  const params = new URLSearchParams(url.search)
  
  params.set('per_page', perPage.toString())
  params.delete('page') // Reset to first page when changing page size
  
  const queryString = params.toString()
  const newUrl = queryString ? `${url.pathname}?${queryString}` : url.pathname
  
  router.visit(newUrl, {
    preserveState: true,
    preserveScroll: true
  })
}
</script>


