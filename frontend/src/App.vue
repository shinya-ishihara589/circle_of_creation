<template>
  <div>
    <h1>{{ msg }}</h1>
    <ul>
      <li v-for="comment in comments" :key="comment.id">
        {{ comment.body }} - <strong>{{ comment.author }}</strong>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    msg: String
  },
  data() {
    return {
      comments: []
    }
  },
  mounted() {
    axios.get('http://localhost:8001/api/comments')
      .then(response => {
        this.comments = response.data
      })
      .catch(error => {
        console.error('API取得エラー:', error)
      })
  }
}
</script>
