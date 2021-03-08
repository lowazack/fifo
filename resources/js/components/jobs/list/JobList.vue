<template>
  <CDataTable
  class="mb-0 table-outline"
  hover
  :items="jobs"
  :fields="fields"
  :loading="loading"
  :sorterValue="sortBy"
  head-color="light"
  v-on:row-clicked="viewJob"
  >
    <td slot="#" slot-scope="{item}" class="text-center">
      {{ item.id }}
    </td>
    <td slot="job" slot-scope="{item}" class="cursor-pointer">
      <div>{{ item.name }}</div>
      <div class="small text-muted">
        <span v-if="item.clients">
          <CButton
          v-for="client in item.clients"
          :key="client.id"
          variant="outline"
          color="secondary"
          size="sm"
          :to="{ name: 'Client', params: { clientId: client.id }}"
          >{{ client.name }}</CButton>
        </span>
      </div>
    </td>
    <td slot="provider" slot-scope="{item}" class="text-center">
      <CIcon :name="`cib-${item.provider}`" />
    </td>
  </CDataTable>
</template>
<script>

export default {
  name: 'JobList',
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
    jobs: {
      default: () => [],
      type: Array
    },
    fields: {
      default: () => [
        { key: '#', _classes: "text-center" },
        { key: 'job' },
        { key: 'provider', _classes: "text-center" }
      ],
      type: Array
    }
  },
  mounted() {
  },
  methods: {
    viewJob(job, index, column, event){
      this.$router.push({ name: 'Job', params: { jobId: job.id } })
    }
  }
}
</script>