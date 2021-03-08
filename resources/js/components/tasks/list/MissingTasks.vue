<template>
    <ul>
      <li :key="task.id" v-for="task in tasks">
        <span @click="openTaskSidebar(task)">[{{task.id}}] {{ task.summary }}</span>
        <CLink :href="task.link" target="_blank" v-if="task.link"><CIcon name="cib-asana" /></CLink>
      </li>
    </ul>
</template>

<script>
export default {
  props: {
    collapse: Boolean,
    tasks: Array
  },
  methods: {
    openTaskSidebar(task) {
      this.$root.$emit('aside', 'TaskSidebar', { 'task': task });
    }
  }
}
</script>