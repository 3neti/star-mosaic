<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const images = ref([])

onMounted(async () => {
    try {
        const response = await axios.get(route('spotify.images'))
        images.value = response.data.map(img => ({
            url: img.cover,
            name: beautify(img.name),
        }))
    } catch (e) {
        console.error('Error loading images:', e)
    }
})

function beautify(filename) {
    return filename
        .replace(/^\d+-/, '')         // remove numeric prefix
        .replace(/-\w{22}\.jpg$/, '') // remove Spotify album ID suffix
        .replace(/\.jpg$/, '')        // in case no ID suffix
        .replace(/-/g, ' ')           // replace dashes with spaces
        .replace(/\b\w/g, c => c.toUpperCase()) // capitalize words
}
</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">🎵 Album Cover Collage</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
            <div
                v-for="img in images"
                :key="img.url"
                class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition-all"
            >
                <img
                    :src="img.url"
                    :alt="img.name"
                    class="w-full object-cover h-40"
                />
                <div class="p-2 text-xs text-center truncate text-gray-700">{{ img.name }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
img {
    border-radius: 0.5rem;
}
</style>
