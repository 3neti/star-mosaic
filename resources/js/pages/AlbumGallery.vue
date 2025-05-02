<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const covers = ref([]);
const playlistId = '5pn8zwd2XxLLZIRyNWhdma'; // Replace or pass as prop

// onMounted(async () => {
//     try {
//         const response = await axios.get(route('spotify.playlist.covers'), {
//             params: { playlist_id: playlistId },
//         });
//         covers.value = response.data;
//     } catch (error) {
//         console.error('Error fetching playlist data:', error);
//     }
// });
</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Album Gallery</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div
                v-for="(cover, index) in covers"
                :key="index"
                class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition-all"
            >
                <img
                    :src="cover.cover"
                    :alt="cover.name"
                    class="w-full object-cover h-48"
                />
                <div class="p-2 text-sm text-center truncate">{{ cover.name }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
img {
    border-radius: 0.5rem;
}
</style>
