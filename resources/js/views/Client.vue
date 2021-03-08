<template>
  <CRow>
    <CCol md="12">
      <JobList :loading="jobsLoading" :jobs="jobs" v-on:statusChange="statusChange" />
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import JobList from "../components/jobs/list/JobList"

export default {
  name: 'Client',
  computed: {
  },
  components: {
    JobList
  },
  data(){
    return {
      showErrors: false,
      jobsLoading: true,
      jobs: [],
    }
  },
  mounted() {
    let vm = this;
    let loadJobs = axios.getAll('/jobs').then(res => {
      vm.setJobs(res.data)
    });

    Promise.all([loadMyJobs, loadAllJobs]).then(function() {
      vm.jobsLoading = false;
    });
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