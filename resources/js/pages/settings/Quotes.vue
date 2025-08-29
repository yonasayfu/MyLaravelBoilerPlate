<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import SettingsLayout from '@/layouts/settings/Layout.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Separator } from '@/components/ui/separator'
import { quotesDestroy, quotesIndex, quotesStore, quotesUpdate, quotesRestore } from '@/routes'
import type { AppPageProps, BreadcrumbItem } from '@/types'
import { Pin } from 'lucide-vue-next'

interface QuoteItem { id: number; text: string; author?: string | null; language?: string | null; pinned: boolean; priority?: number | null; image_url?: string | null }

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Quotes', href: quotesIndex().url },
]

const page = usePage<AppPageProps<{ quotes: QuoteItem[]; trashed: QuoteItem[] }>>()
const quotes = ref([...page.props.quotes])
const trashed = ref([...page.props.trashed])

const form = useForm<{ text: string; author: string; language: string; priority: number | ''; image: File | null }>({ text: '', author: '', language: '', priority: '' as unknown as number | '', image: null })
const imageInputRef = ref<HTMLInputElement | null>(null)

function onCreateImageChange(e: Event) {
  const t = e.target as HTMLInputElement
  const f = t.files && t.files[0]
  form.image = f ?? null
}

function submit() {
  form.post(quotesStore(), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      form.reset('text', 'author', 'language', 'priority', 'image')
      if (imageInputRef.value) imageInputRef.value.value = ''
    },
  })
}

function pin(q: QuoteItem) {
  const form = useForm({ pinned: !q.pinned });
  form.patch(quotesUpdate(q.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Create a new array to trigger reactivity
      const updatedQuotes = [...quotes.value];
      const index = updatedQuotes.findIndex(quote => quote.id === q.id);
      
      if (index !== -1) {
        // Toggle the pinned state
        const newPinnedState = !q.pinned;
        updatedQuotes[index] = { ...updatedQuotes[index], pinned: newPinnedState };
        
        // If pinning, unpin any other pinned quotes
        if (newPinnedState) {
          updatedQuotes.forEach((quote, i) => {
            if (quote.id !== q.id && quote.pinned) {
              updatedQuotes[i] = { ...quote, pinned: false };
            }
          });
        }
        
        // Update the reactive reference
        quotes.value = updatedQuotes;
      }
      
      // Client-side persistence for guests after logout
      try {
        if (!q.pinned) {
          const payload = { 
            message: q.text, 
            author: q.author ?? undefined, 
            image: q.image_url ?? undefined 
          };
          window.localStorage.setItem('guestPinnedQuote', JSON.stringify(payload));
        } else {
          window.localStorage.removeItem('guestPinnedQuote');
        }
      } catch {}
    },
  });
}

function updatePriority(id: number, priority: number | null) {
  useForm({ priority }).patch(quotesUpdate(id), { preserveScroll: true })
}

function remove(id: number) {
  useForm({}).delete(quotesDestroy(id), { preserveScroll: true })
}

function restore(id: number) {
  useForm({}).post(quotesRestore(id), { preserveScroll: true })
}

function onUpdateImageChange(id: number, e: Event) {
  const t = e.target as HTMLInputElement
  const f = t.files && t.files[0]
  if (!f) return
  const formImg = useForm<{ image: File | null }>({ image: f })
  formImg.post(quotesUpdate(id), { preserveScroll: true, forceFormData: true, onFinish: () => { t.value = '' } })
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Quotes" />

    <SettingsLayout>
      <div class="flex flex-col space-y-8">
        <HeadingSmall title="Add a quote" description="Manage personal quotes shown on auth pages" />

        <form @submit.prevent="submit" class="grid grid-cols-1 gap-4 sm:grid-cols-3" enctype="multipart/form-data">
          <div class="sm:col-span-2 grid gap-2">
            <Label for="text">Quote text</Label>
            <Input id="text" v-model="form.text" placeholder="Write your quote…" required />
          </div>
          <div class="grid gap-2">
            <Label for="author">Author (optional)</Label>
            <Input id="author" v-model="form.author" placeholder="Author name" />
          </div>
          <div class="grid gap-2">
            <Label for="language">Language (optional)</Label>
            <Input id="language" v-model="form.language" placeholder="en, am, ..." />
          </div>
          <div class="grid gap-2 sm:col-span-2">
            <Label for="priority">Priority (optional)</Label>
            <Input id="priority" type="number" min="0" v-model.number="form.priority" placeholder="Lower shows first" />
          </div>
          <div class="grid gap-2 sm:col-span-3">
            <Label for="image">Image (optional)</Label>
            <Input id="image" type="file" accept="image/*" @change="onCreateImageChange" ref="imageInputRef" />
          </div>
          <div class="flex items-end">
            <Button :disabled="form.processing">Add quote</Button>
          </div>
        </form>

        <Separator />

        <div class="space-y-4">
          <HeadingSmall title="Your quotes" description="Pin one as default or manage priorities" />

          <div v-if="quotes.length === 0" class="text-sm text-muted-foreground">No quotes yet.</div>

          <ul v-else class="divide-y rounded-lg border">
            <li v-for="q in quotes" :key="q.id" class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
              <div class="flex-1">
                <p class="font-medium" :class="{ 'italic': q.pinned }">{{ q.text }}</p>
                <p class="text-xs text-muted-foreground">
                  <span v-if="q.pinned" class="mr-2">Pinned</span>
                  <span v-if="q.author" class="mr-2">Author: {{ q.author }}</span>
                  <span v-if="q.language">Lang: {{ q.language }}</span>
                </p>
              </div>
              <div class="flex items-center gap-2 sm:w-80">
                <Input type="number" class="w-28" :value="q.priority ?? ''" placeholder="Priority" @change="(e: any) => updatePriority(q.id, e.target.value ? Number(e.target.value) : null)" />
                <Button
                  type="button"
                  :variant="q.pinned ? 'default' : 'outline'"
                  class="p-2 h-9 w-9 flex items-center justify-center"
                  :title="q.pinned ? 'Unpin this quote' : 'Pin this quote'"
                  @click="() => pin(q)"
                >
                  <Pin :class="['h-4 w-4', { 'fill-current': q.pinned }]" />
                </Button>
                <Button variant="ghost" @click="remove(q.id)" class="text-red-600 hover:text-red-700">Delete</Button>
              </div>
            </li>
          </ul>
        </div>

        <Separator />

        <div class="space-y-4">
          <HeadingSmall title="Trash" description="Recently deleted quotes" />
          <div v-if="trashed.length === 0" class="text-sm text-muted-foreground">No deleted quotes.</div>
          <ul v-else class="divide-y rounded-lg border bg-muted/30">
            <li v-for="t in trashed" :key="t.id" class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
              <div class="flex-1">
                <p class="font-medium">{{ t.text }}</p>
                <p class="text-xs text-muted-foreground">
                  <span v-if="t.author" class="mr-2">Author: {{ t.author }}</span>
                  <span v-if="t.language">Lang: {{ t.language }}</span>
                </p>
              </div>
              <div class="flex items-center gap-2 sm:w-80">
                <Button variant="ghost" @click="restore(t.id)">Restore</Button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
