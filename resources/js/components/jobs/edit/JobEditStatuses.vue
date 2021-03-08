<template>
  <CRow>
    <CCol col="12" class="text-right">
      <CButton
        @click="openNewJobStatusSidebar()"
        color="primary"
        class="mb-2"
      >
        <CIcon name="cil-plus"/> Add new status
      </CButton>
    </CCol>
    <CCol col="12">
      <draggable
        v-model="statuses"
        class="list-group"
        ghost-class="ghost"
        @end="rewriteOrders"
      >
        <CListGroupItem class="d-flex align-items-center cursor-pointer" :class="`fifo-color-lighter-${status.color} with-hover`" v-for="status in statuses" :key="status.id" @click="openJobStatusSidebar(status)">
          <span class="mr-auto">{{ status.name }}</span>
          <CIcon v-if="status.completed" name="cil-check-circle" v-c-tooltip="'When tasks are moved to this status, they are automatically marked complete'" class="mx-3" />
          <CBadge size="lg" :class="`fifo-color-${status.color}`"><p class="m-0 p-1">{{ status.color | uppercase }}</p></CBadge>
        </CListGroupItem>
      </draggable>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import draggable from "vuedraggable";

export default {
  name: 'JobEditStatuses',
  components: {
    draggable
  },
  data(){
    return {
      statuses: [],
      showErrors: false,
    }
  },
  props: {
    job: {
      type: Object,
      required: true
    }
  },
  watch: {
    job: {
      deep: true,
      handler: function(newVal, oldVal){
        this.statuses = this.job.statuses;
      }
    }
  },
  methods: {
    rewriteOrders: function() {
      var i = 0;
      var vm = this;
      var promises = [];
      this.statuses.forEach(function(status, index){
        vm.statuses[index].order = i;
        i++;
        //delete vm.statuses[index].job;
        promises.push(axios.put(`/jobs/${status.job.id}/statuses/${status.id}`, status));
      });
      axios.all(promises).then((responses)=>{
        vm.$root.$emit('toast', {
          title: 'Statuses Updated',
          content: `<b>${i}</b> statuses have been updated.`
        });
      })
    },
    openNewJobStatusSidebar() {
      this.$root.$emit('aside', 'JobStatusSidebar', { 'job': this.job });
    },
    openJobStatusSidebar(status) {
      this.$root.$emit('aside', 'JobStatusSidebar', { 'status': status });
    }
  }
}
</script>
