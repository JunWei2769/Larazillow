<template>
    <Box>
        <template #header>Upload New Images</template>
        <form @submit.prevent="upload">
            <section class="flex items-center gap-2 my-4">
                <input
                    class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:hover:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"
                    type="file" multiple @input="addFiles" /> <!-- 'multiple' allows for uploading multiple files -->
                <button type="submit" class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
                    :disabled="!canUpload">Upload</button>
                <button type="reset" @click="reset" class="btn-outline">Reset</button>
            </section>
            <div v-if="imageErrors.length" class="input-error">
                <div v-for="(error, index) in imageErrors" :key="index">
                    {{ error }}
                </div>
            </div>
        </form>
    </Box>

    <Box v-if="listing.images.length" class="mt-4">
        <template #header>Current Listing Images</template>
        <section class="mt-4 grid grid-cols-3 gap-4">
            <!--Must specify the key once using v-for-->
            <div v-for="image in listing.images" :key="image.id" class="flex flex-col justify-between">
                <img :src="image.src" class="rounded-md" />
                <Link :href="route('realtor.listing.image.destroy', { listing: props.listing.id, image: image.id })"
                    method="DELETE" as="button" class="mt-2 btn-outline text-xs">Delete</Link>
            </div>
        </section>
    </Box>
</template>

<script setup>
import { computed } from 'vue'
import Box from '@/Components/UI/Box.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import NProgress from 'nprogress'

const props = defineProps({ listing: Object })

Inertia.on('progress', (event) => {         // progress bar
    if (event.detail.progress.percentage) {
        NProgress.set((event.detail.progress.percentage / 100) * 0.9)
    }
})

const form = useForm({
    images: [],
})

const imageErrors = computed(() => Object.values(form.errors))      // Turn object to array

const canUpload = computed(() => form.images.length)    // to determine whether there is image chosen or not

const upload = () => {
    form.post(      // use post method to submit the form
        route('realtor.listing.image.store', { listing: props.listing.id }),
        {
            onSuccess: () => form.reset('images'),  // reset images field (to prevent accidentally upload the same image twice)
        },
    )
}

const addFiles = (event) => {
    for (const image of event.target.files) {     // get the uploaded files (iterate all the elements)
        form.images.push(image)     // add images to the 'images' array (push() method)
    }
}

const reset = () => form.reset('images')        // execute when the reset button is clicked
</script>