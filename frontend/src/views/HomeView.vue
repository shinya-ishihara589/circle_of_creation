<script lang="ts" setup>
import api from '@/libs/api';

const load = async () => {
  const res = await api.get('/test');
  console.log('API Response:', res.data);
};

load();
</script>

<template>
  <div>API テスト中…（結果はコンソールに表示）</div>
</template>
