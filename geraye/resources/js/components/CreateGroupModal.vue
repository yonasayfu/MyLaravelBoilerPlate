<template>
  <div v-if="show" class="fixed inset-0 bg-black/50 z-[99999] flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Create New Group</h3>
        <button @click="$emit('close')" class="text-muted-foreground hover:text-foreground">
          <X class="w-5 h-5" />
        </button>
      </div>

      <form @submit.prevent="createGroup">
        <div class="mb-4">
          <label for="groupName" class="block text-sm font-medium text-foreground mb-1">Group Name</label>
          <input
            type="text"
            id="groupName"
            v-model="form.name"
            class="form-input w-full rounded-md border border-input px-3 py-2 text-sm bg-background text-foreground"
            required
          />
          <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
        </div>

        <div class="mb-4">
          <label for="members" class="block text-sm font-medium text-foreground mb-1">Add Members</label>
          <select
            id="members"
            v-model="form.members"
            multiple
            class="form-select w-full rounded-md border border-input px-3 py-2 text-sm bg-background text-foreground h-32"
          >
            <option v-for="user in availableUsers" :key="user.id" :value="user.id">{{ user.name }}</option>
          </select>
          <p v-if="form.errors.members" class="text-red-500 text-xs mt-1">{{ form.errors.members }}</p>
        </div>

        <div class="flex justify-end gap-2">
          <button type="button" @click="$emit('close')" class="px-4 py-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-muted transition">Cancel</button>
          <button type="submit" :disabled="form.processing" class="px-4 py-2 rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 transition">
            {{ form.processing ? 'Creating...' : 'Create Group' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
import axios from 'axios';
import type { User } from '@/types';

const props = defineProps<{ show: boolean }>();
const emit = defineEmits(['close', 'groupCreated']);

const form = useForm({
  name: '',
  members: [] as number[],
});

const availableUsers = ref<User[]>([]);

const fetchAvailableUsers = async () => {
  try {
    // Assuming an API endpoint to get all users for group creation
    const response = await axios.get(route('users.index')); // You might need to create this route
    availableUsers.value = response.data.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

const createGroup = async () => {
  form.post(route('groups.create'), {
    onSuccess: (page) => {
      emit('groupCreated', page.props.group);
      form.reset();
      emit('close');
    },
    onError: (errors) => {
      console.error('Error creating group:', errors);
    },
  });
};

onMounted(() => {
  fetchAvailableUsers();
});
</script>
