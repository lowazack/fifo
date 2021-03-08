<template>
  <CDataTable
  class="mb-0 table-outline"
  hover
  :items="tasks"
  :fields="fields"
  :loading="loading"
  head-color="light"
  >
    <td slot="#" slot-scope="{item}" class="text-center">
      {{ item.id }}
    </td>
    <td slot="summary" slot-scope="{item}" class="cursor-pointer" @click="openTaskSidebar(item)">
      <div>{{ item.summary ? item.summary : 'Summary Unavailable'  }}</div>
      <div class="small text-muted">
        <span v-if="item.job">{{ item.job.name }}</span>
      </div>
    </td>
    <td slot="estimate" slot-scope="{item}" class="text-center">
      <span v-if="item.estimate">{{ item.estimate }}h</span>
    </td>
    <td slot="link" slot-scope="{item}" class="text-center">
      <CLink v-if="item.link" :href="item.link" target="_blank">
        <CIcon :name="`cib-${item.provider}`" />
      </CLink>
    </td>
    <td slot="priority" slot-scope="{item}" class="text-center">
      <star-rating v-model="item.priority" class="justify-content-center" :read-only="true" :showRating="false" :starSize="10" :roundedCorners="true" :borderWidth="3" activeColor="#768192" activeBorderColor="#768192" inactiveColor="#d8dbe0" borderColor="#d8dbe0" />
    </td>
    <td slot="assignee" slot-scope="{item}">
      <CLink v-if="item.user" :href="`mailto:${item.user.email}`" target="_blank">{{ item.user.first_name }}</CLink>
      <p v-else class="text-muted">Unassigned</p>
    </td>
    <td slot="status" slot-scope="{item}" class="text-center">
      <CSwitch :key="item.id" :checked="item.complete" :name="item.id" v-on:update:checked="statusChange" color="success" variant="3d" />
    </td>
  </CDataTable>
</template>
<script>

export default {
  name: 'TaskList',
  computed: {
  },
  components: {
  },
  data(){
    return {
    }
  },
  props: {
    loading: {
      default: false,
      type: Boolean
    },
    sortBy: {
      default: () => ({
        column: 'priority',
        asc: false
      }),
      type: Object
    },
    tasks: {
      default: () => [],
      type: Array
    },
    fields: {
      default: () => [
        { key: '#', _classes: "text-center" },
        { key: 'summary' },
        { key: 'estimate', _classes: "text-center" },
        { key: 'link', _classes: "text-center" },
        { key: 'priority', _classes: "text-center" },
        { key: 'assignee' },
        { key: 'status', _classes: "text-center" }
      ],
      type: Array
    }
  },
  mounted() {
  },
  methods: {
    statusChange(value, $event) {
      this.$emit('statusChange', value, $event);
    },
    openTaskSidebar(task) {
      this.$root.$emit('aside', 'TaskSidebar', { 'task': task });
    },
    priorityBadgeColor(priority){
      var priorities = {
        '1': '',
        '2': 'secondary',
        '3': 'success',
        '4': 'warning',
        '5': 'danger'
      }
      return priorities[priority];
    },
    priorityBadgeText(priority){
      var priorities = {
        '1': 'None',
        '2': 'Low',
        '3': 'Medium',
        '4': 'High',
        '5': 'Urgent'
      }
      return priorities[priority];
    }
  }
}
</script>