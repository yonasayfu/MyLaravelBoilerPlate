<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home, register } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { computed } from 'vue';

const page = usePage();
const name = page.props.name;
const quote = page.props.quote as { message: string; author?: string } | undefined;
const natureBackgrounds = [
    'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1600&auto=format&fit=crop', // mountain valley
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop', // forest mist
    'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1600&auto=format&fit=crop', // lake sunrise
    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1600&auto=format&fit=crop', // ocean waves
    'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1600&auto=format&fit=crop', // repeat for safety
];

const sampleQuotes = [
    { message: 'Quality is not an act, it is a habit.', author: 'Aristotle' },
    { message: 'Simplicity is the soul of efficiency.', author: 'Austin Freeman' },
    { message: 'First, solve the problem. Then, write the code.', author: 'John Johnson' },
    { message: 'Make it work, make it right, make it fast.', author: 'Kent Beck' },
    { message: 'Small gains, consistently.', author: 'Geraye Principles' },
];

// Single random index per page load and pair with quote; if backend quote exists, derive index from quote text
const randomIndex = Math.floor(Math.random() * natureBackgrounds.length);
const displayQuote = computed(() => quote ?? sampleQuotes[randomIndex % sampleQuotes.length]);
const pickIndex = computed(() => {
    if (quote?.message) {
        let h = 0;
        for (let i = 0; i < quote.message.length; i++) {
            h = (h << 5) - h + quote.message.charCodeAt(i);
            h |= 0; // Convert to 32bit integer
        }
        return Math.abs(h) % natureBackgrounds.length;
    }
    return randomIndex % natureBackgrounds.length;
});
const backgroundUrl = computed(() => natureBackgrounds[pickIndex.value]);

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid min-h-svh flex-col items-center justify-center bg-muted px-6 sm:px-8 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <!-- Left visual/brand panel -->
        <div class="relative hidden h-full flex-col items-center justify-center p-10 text-white lg:flex dark:border-r bg-cover bg-center bg-no-repeat" :style="{ backgroundImage: `url(${backgroundUrl})` }">
            <div class="absolute inset-0 bg-gradient-to-b from-cyan-900/60 to-slate-900/60" />
            <Link :href="home()" class="relative z-20 flex items-center text-lg font-medium">
                <AppLogoIcon class="mr-2 size-8 fill-current text-white" />
                {{ name }}
            </Link>
            <div class="relative z-20 mt-8 max-w-xl text-center">
                <div class="liquidGlass-wrapper">
                    <div class="liquidGlass-content">
                        <blockquote class="space-y-3">
                            <p class="text-2xl md:text-3xl font-semibold tracking-tight leading-relaxed drop-shadow">&ldquo;{{ displayQuote.message }}&rdquo;</p>
                            <footer class="text-base text-neutral-200/90">{{ displayQuote.author }}</footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right auth card panel -->
        <div class="lg:p-8">
            <div class="mx-auto flex w-full max-w-md flex-col justify-center">
                <div class="mb-4 flex w-full items-center justify-end">
                    <Link :href="register().url" class="btn btn-glass-cream">Create account</Link>
                </div>
                <Card class="rounded-xl">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="text-xl" v-if="title">{{ title }}</CardTitle>
                        <CardDescription v-if="description">{{ description }}</CardDescription>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
