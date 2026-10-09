<template>
  <div class="layout">
    <Menu />
    <ApiTest />
  </div>
</template>

<script setup lang="ts">
import Menu from '@/components/Menu.vue';
import ApiTest from '@/components/ApiTest.vue';
</script>

<style scoped>
.layout {
  display: flex;
}
</style>
