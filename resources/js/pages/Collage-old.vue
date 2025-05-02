<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const images = ref([])

onMounted(async () => {
    const response = await axios.get(route('spotify.images'))
    images.value = response.data
})
</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">🎵 Album Cover Collage</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
            <img
                v-for="img in images"
                :key="img.name"
                :src="img.url"
                class="object-cover w-full h-32 sm:h-40 md:h-48 rounded shadow"
                :alt="img.name"
            />
        </div>
    </div>
</template>
