<template>
  <CRow>
    <CCol md="12">
      <h1>{{ job.name }}</h1>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";

export default {
  name: 'Job',
  components: {
  },
  data(){
    return {
      showErrors: false,
      jobsLoading: true,
      jobs: [],
    }
  },
  props: {
    job: {
      type: [Object, Array],
      required: true
    }
  },
  computed: {
    route() {
        return this.$route.name;
    }
  },
  methods: {
    setJobs(jobs){
      this.jobs = Object.keys(jobs).map((key) => jobs[key])
    },
    getJob(jobId){
      return this.jobs.find(job => job.id == jobId);
    },
    statusChange(value, Event) {
      let job = this.getJob(Event.target.name);
      if(job){
        let formObject = {
          id: Event.target.name,
          value: true
        }
        axios.post('/job/update', formObject).then(res => {
          this.setJobs(res.data)
        })
        this.$root.$emit('toast', {
          title: 'Complete!',
          content: `<b>${job.summary}</b> has been completed.`,
          color: 'success'
        });
      }else{
        this.$root.$emit('toast', {
          title: 'Error',
          content: 'Could not find that job',
          color: 'danger'
        });
      }
    }
  }
}
</script>