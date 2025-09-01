<template>
  <Teleport to="body">
    <Transition name="modal">
      <div
        v-if="isVisible"
        class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4"
        @click="handleBackdropClick"
      >
        <div
          class="modal-content relative"
          @click.stop
        >
          <!-- Icon -->
          <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full"
               :class="getIconBackgroundClass(type)">
            <component
              :is="getIcon(type)"
              class="w-6 h-6"
              :class="getIconClass(type)"
            />
          </div>

          <!-- Content -->
          <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
              {{ title }}
            </h3>
            <p v-if="message" class="text-sm text-gray-500 dark:text-gray-400 mb-6">
              {{ message }}
            </p>
          </div>

          <!-- Actions -->
          <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
            <button
              ref="cancelButtonRef"
              type="button"
              class="btn-secondary w-full sm:w-auto"
              @click="handleCancel"
            >
              {{ cancelText }}
            </button>
            <button
              type="button"
              class="w-full sm:w-auto"
              :class="getConfirmButtonClass(type)"
              @click="handleConfirm"
              :disabled="isLoading"
            >
              <span v-if="isLoading" class="loading-spinner mr-2"></span>
              {{ isLoading ? loadingText : confirmText }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, nextTick, onMounted, onUnmounted } from 'vue'
import { 
  AlertTriangle, 
  Trash2, 
  CheckCircle, 
  Info, 
  XCircle 
} from 'lucide-vue-next'

export interface ConfirmOptions {
  type?: 'danger' | 'warning' | 'info' | 'success'
  title: string
  message?: string
  confirmText?: string
  cancelText?: string
  loadingText?: string
  onConfirm?: () => void | Promise<void>
  onCancel?: () => void
}

const isVisible = ref(false)
const isLoading = ref(false)
const cancelButtonRef = ref<HTMLButtonElement>()

const type = ref<ConfirmOptions['type']>('warning')
const title = ref('')
const message = ref('')
const confirmText = ref('Confirm')
const cancelText = ref('Cancel')
const loadingText = ref('Loading...')
const onConfirm = ref<(() => void | Promise<void>) | null>(null)
const onCancel = ref<(() => void) | null>(null)

const show = (options: ConfirmOptions) => {
  type.value = options.type || 'warning'
  title.value = options.title
  message.value = options.message || ''
  confirmText.value = options.confirmText || 'Confirm'
  cancelText.value = options.cancelText || 'Cancel'
  loadingText.value = options.loadingText || 'Loading...'
  onConfirm.value = options.onConfirm || null
  onCancel.value = options.onCancel || null
  
  isVisible.value = true
  
  nextTick(() => {
    cancelButtonRef.value?.focus()
  })
}

const hide = () => {
  isVisible.value = false
  isLoading.value = false
  
  // Reset values
  type.value = 'warning'
  title.value = ''
  message.value = ''
  confirmText.value = 'Confirm'
  cancelText.value = 'Cancel'
  loadingText.value = 'Loading...'
  onConfirm.value = null
  onCancel.value = null
}

const handleConfirm = async () => {
  if (onConfirm.value) {
    try {
      isLoading.value = true
      await onConfirm.value()
      hide()
    } catch (error) {
      isLoading.value = false
      console.error('Confirmation action failed:', error)
    }
  } else {
    hide()
  }
}

const handleCancel = () => {
  if (onCancel.value) {
    onCancel.value()
  }
  hide()
}

const handleBackdropClick = () => {
  handleCancel()
}

const handleKeydown = (event: KeyboardEvent) => {
  if (!isVisible.value) return
  
  if (event.key === 'Escape') {
    handleCancel()
  } else if (event.key === 'Enter') {
    handleConfirm()
  }
}

const getIcon = (type: ConfirmOptions['type']) => {
  const icons = {
    danger: Trash2,
    warning: AlertTriangle,
    info: Info,
    success: CheckCircle
  }
  return icons[type || 'warning']
}

const getIconClass = (type: ConfirmOptions['type']) => {
  const classes = {
    danger: 'text-red-600',
    warning: 'text-yellow-600',
    info: 'text-blue-600',
    success: 'text-green-600'
  }
  return classes[type || 'warning']
}

const getIconBackgroundClass = (type: ConfirmOptions['type']) => {
  const classes = {
    danger: 'bg-red-100 dark:bg-red-900/20',
    warning: 'bg-yellow-100 dark:bg-yellow-900/20',
    info: 'bg-blue-100 dark:bg-blue-900/20',
    success: 'bg-green-100 dark:bg-green-900/20'
  }
  return classes[type || 'warning']
}

const getConfirmButtonClass = (type: ConfirmOptions['type']) => {
  const classes = {
    danger: 'btn-danger',
    warning: 'btn-primary',
    info: 'btn-primary',
    success: 'btn-success'
  }
  return classes[type || 'warning']
}

// Expose methods
defineExpose({
  show,
  hide
})

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-content,
.modal-leave-to .modal-content {
  transform: scale(0.9);
}
</style>
