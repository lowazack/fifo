<template>
  <CRow>
    <CCol md="12" class="text-right" v-if="missingJobs.length || missingDuration.length">
      <CButton
          @click="showErrors = !showErrors"
          color="danger"
          class="mb-2"
      >
        <CIcon name="cil-warning"/>
        Some errors detected
      </CButton>
      <CCollapse class="text-left" :show="showErrors">
        <CCard>
          <CCardHeader>
            <slot name="header">
              <CIcon name="cil-grid"/>
              Tasks with problems
            </slot>
          </CCardHeader>
          <CCardBody class="m-0 alert alert-danger">
            <CTabs>
              <CTab v-if="missingJobs.length">
                <template slot="title">
                  <CIcon name="cil-folder-open"/>
                  Orphaned
                </template>
                <p class="pt-3 pb-0 pl-4 m-0">The following tasks are not assigned to jobs:</p>
                <missing-tasks class="m-0 p-3 pt-0 pl-5" :tasks="missingJobs" :collapse="collapseJobs"/>
              </CTab>
              <CTab v-if="missingDuration.length">
                <template slot="title">
                  <CIcon name="cil-alarm"/>
                  Missing Duration
                </template>
                <p class="pt-3 pb-0 pl-4 m-0">The following tasks do not have an estimate set, so can't be
                  scheduled:</p>
                <missing-tasks class="m-0 p-3 pt-0 pl-5" :tasks="missingDuration" :collapse="collapseAssignee"/>
              </CTab>
            </CTabs>
          </CCardBody>
        </CCard>
      </CCollapse>
    </CCol>
    <CCol md="12">
      <TaskList v-if="route == 'Tasks'" :loading="tasksLoading" :tasks="tasks" v-on:statusChange="statusChange"/>
      <TaskList v-if="route == 'TasksAll'" :loading="tasksLoading" :tasks="allTasks" v-on:statusChange="statusChange"/>
      <TaskList v-if="route == 'TasksCompleted'" :loading="tasksLoading" :tasks="completedTasks"
                v-on:statusChange="statusChange"/>
      <TaskList v-if="route == 'TasksDeleted'" :loading="tasksLoading" :tasks="deletedTasks"
                v-on:statusChange="statusChange"/>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import MissingTasks from "../components/tasks/list/MissingTasks"
import TaskList from "../components/tasks/list/TaskList"

export default {
  name: 'Tasks',
  computed: {
    route() {
      return this.$route.name;
    },
    missingJobs() {
      return this.allTasks.filter(task => {
        return task.job === null
      })
    },
    missingDuration() {
      return this.allTasks.filter(task => {
        return task.estimate === null
      })
    }
  },
  components: {
    MissingTasks,
    TaskList
  },
  data() {
    return {
      showErrors: false,
      tasksLoading: true,
      tasks: [],
      allTasks: [],
      completedTasks: [],
      deletedTasks: [],
      collapseJobs: true,
      collapseDuration: true,
      collapseAssignee: true,
      collapsePriority: true,
    }
  },
  mounted() {
    let loadMyTasks = axios.getAll('/me/tasks').then(res => {
      this.setTasks(res.data)
    });
    let loadAllTasks = axios.getAll('/tasks').then(res => {
      this.setAllTasks(res.data)
    });
    let loadCompletedTasks = axios.getAll('/tasks?filter[onlycompleted]=1').then(res => {
      this.setCompleteTasks(res.data)
    });
    let loadDeletedTasks = axios.getAll('/tasks?filter[trashed]=only').then(res => {
      this.setDeletedTasks(res.data)
    });
    Promise.all([loadMyTasks, loadAllTasks, loadCompletedTasks, loadDeletedTasks]).then(() => {
      this.tasksLoading = false;
    });

    Echo.channel('tasks')
        .listen('.TaskUpdated', () => {
          this.updateTasks()
        })
        .listen('.TaskCreated', () => {
          this.updateTasks()
        })
        .listen('.TaskDeleted', () => {
          this.updateTasks()
        });
  },
  methods: {
    setTask(task) {
      let taskKey = this.tasks.findIndex((item => item.id === task));
      this.tasks[taskKey] = task;
    },
    setTasks(tasks) {
      this.tasks = Object.keys(tasks).map((key) => tasks[key])
    },
    setAllTasks(tasks) {
      this.allTasks = Object.keys(tasks).map((key) => tasks[key])
    },
    setCompleteTasks(tasks) {
      this.completedTasks = Object.keys(tasks).map((key) => tasks[key])
    },
    setDeletedTasks(tasks) {
      this.deletedTasks = Object.keys(tasks).map((key) => tasks[key])
    },
    getTask(taskId) {
      let task = this.allTasks.find(task => task.id == taskId)
      if (task) {
        return this.allTasks.find(task => task.id == taskId);
      } else {
        return this.completedTasks.find(task => task.id == taskId);
      }
    },
    statusChange(value, Event) {
      let task = this.getTask(Event.target.name);
      if (task) {
        task.complete = !!value;
        axios.put(`/tasks/${task.id}?filter[withcompleted]=1`, task).then(res => {
          this.setTask(res.data)
        })
        this.$root.$emit('toast', {
          title: 'Complete!',
          content: `<b>${task.summary}</b> has been completed.`
        });
      } else {
        this.$root.$emit('toast', {
          title: 'Error',
          content: 'Could not find that task',
          color: 'danger'
        });
      }
    },
    updateTasks() {
      axios.getAll('/me/tasks').then(res => {
        this.setTasks(res.data)
      });
      axios.getAll('/tasks').then(res => {
        this.setAllTasks(res.data)
      });
      axios.getAll('/tasks?filter[onlycompleted]=1').then(res => {
        this.setCompleteTasks(res.data)
      });
      axios.getAll('/tasks?filter[trashed]=only').then(res => {
        this.setDeletedTasks(res.data)
      });
    }
  }
}
</script>