<template>
    <span>
        {{ formattedPrice }}
    </span>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
    price: [Number, String],        // price can be accepted in Number or String format
})
const formattedPrice = computed(
    // Casting price to number as toLocaleString() method only works on Number type
    // () => Number(props.price).toLocaleString('en-US', {
    //     style: 'currency',
    //     currency: 'USD',
    //     maximumFractionDigits: 0,
    // }),
    () => Number(props.price).toLocaleString('ms-MY', {
        style: 'currency',
        currency: 'MYR',
        maximumFractionDigits: 0,
    })
)
</script>