<script lang="ts" setup>
import api from '@/libs/api';

const load = async () => {
  const res = await api.get('/test');
  console.log('API Response:', res.data);
};

load();
</script>

<template>
  <div class="content">
    <h2>API テスト中…（結果はコンソールに表示）</h2>
  </div>
</template>

<style scoped>
.content {
  padding: 20px;
}
</style>
