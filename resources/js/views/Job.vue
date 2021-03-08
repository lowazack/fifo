<template>
  <keep-alive>
    <component :is="route" :job="job"></component>
  </keep-alive>
</template>

<script>
import axios from "axios";

import Job from '../components/jobs/view/Job'
import JobEditStatuses from '../components/jobs/edit/JobEditStatuses'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'
// import Job from './components/jobs/Job.vue'

export default {
  name: 'JobView',
  components: {
    Job,
    JobEditStatuses
  },
  data(){
    return {
      showErrors: false,
      job: {},
    }
  },
  mounted() {
    this.loadJob();
    Echo.channel('jobs')
    .listen('.JobUpdated', (e) => {
      this.loadJob();
    })
    .listen('.JobCreated', (e) => {
      this.loadJob();
    })
    .listen('.JobDeleted', (e) => {
      this.loadJob();
    })
  },
  computed: {
    route() {
      return this.$route.name;
    },
    jobId(){
      return this.$route.params.jobId;
    },
  },
  watch: {
    jobId: function(newVal, oldVal){
      if(newval !== oldVal){
        this.loadJob();
      }
    }
  },
  methods: {
    loadJob(){
      axios.all([
        axios.get(`/jobs/${this.jobId}`),
        axios.getAll(`/jobs/${this.jobId}/statuses`),
        axios.getAll(`/jobs/${this.jobId}/tasks`)
      ]).then((responses)=>{
        this.job = responses[0].data;
        this.job.statuses = responses[1].data;
        this.job.tasks = responses[2].data;
      })
    }
  }
}
</script>