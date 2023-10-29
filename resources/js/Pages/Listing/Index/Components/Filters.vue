<template>
    <form @submit.prevent="filter">
        <div class="mb-8 mt-4 flex flex-wrap gap-2">
            <div class="flex flex-nowrap items-center">
                <input v-model.number="filterForm.priceFrom" type="text" placeholder="Price from" class="input-filter-l w-28" />
                <input v-model.number="filterForm.priceTo" type="text" placeholder="Price to" class="input-filter-r w-28"/>
            </div>

            <div class="flex flex-nowrap items-center">
                <select v-model.number="filterForm.beds" class="input-filter-l w-28">
                    <option :value="null">Beds</option>
                    <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>     <!-- iterate from 1 to 5, no need to create five <option> element -->
                    <option>6+</option>
                </select>
                <select v-model.number="filterForm.baths" class="input-filter-r w-28">
                    <option :value="null">Baths</option>
                    <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>     <!-- iterate from 1 to 5, no need to create five <option> element -->
                    <option>6+</option>
                </select>
            </div>

            <div class="flex flex-nowrap items-center">
                <input v-model.number="filterForm.areaFrom" type="text" placeholder="Area from" class="input-filter-l w-28"/>
                <input v-model.number="filterForm.areaTo" type="text" placeholder="Area to" class="input-filter-r w-28"/>
            </div>

            <button type="submit" class="btn-normal">Filter</button>
            <button type="reset" @click="clear">Clear</button>      <!-- 'reset' only reset the html form value, but not the value in filterForm, so clear function is used-->
        </div>
    </form>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3'

const props = defineProps({
    filters: Object,
})

const filterForm = useForm({
    priceFrom: props.filters.priceFrom ?? null,     // if it is null or undefined, it will be assigned as null
    priceTo: props.filters.priceTo ?? null,
    beds: props.filters.beds ?? null,
    baths: props.filters.baths ?? null,
    areaFrom: props.filters.areaFrom ?? null,
    areaTo: props.filters.areaTo ?? null,
})

const filter = () => {
    filterForm.get(     // function that sends GET request to listing.index route
        route('listing.index'),
        {
            preserveState: true,        // to preserve the component state when navigating back and forth
            preserveScroll: true,       // to maintain the scroll position when navigating back and forth
        },
    )
}

const clear = () => {       // to reset the value in filterForm
    filterForm.priceFrom = null
    filterForm.priceTo = null
    filterForm.beds = null
    filterForm.baths = null
    filterForm.areaFrom = null
    filterForm.areaTo = null
    filter()
}
</script>