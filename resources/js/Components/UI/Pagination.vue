<template>
    <div class="flex gap-1">
        <!-- When using v-for, we must add a key -->
        <Link v-for="(link, index) in links" 
        :key="index" class="py-2 px-4 rounded-md" :href="link.url"
        :class="{'bg-indigo-500 dark:bg-indigo-800 text-gray-300': link.active}" 
        v-html="link.label" /> <!-- v-html will render the actual HTML code, instead of displaying the text -->
    </div>
</template>

<script setup>
    import {Link} from '@inertiajs/vue3'
    defineProps({links: Array})
</script>