<template>
    <div class="message d-flex align-items-center">
        <div class="mr-3">
            <div class="c-avatar">
                <span class="color-swatch d-block medium rounded shadow-sm" :class="`fifo-color-purple`"></span>
            </div>
        </div>
        <div class="flex-grow-1">
            <small class="text-muted">
          <span v-for="client in entry.task.job.clients" :key="client.id">
              {{
                  client.name
              }} </span> - {{ entry.task.job.name }}</small>
            <div class="font-weight-bold">
                {{ entry.task.summary }}
            </div>
            <div class="font-weight-bold text-muted">
                {{ entry.activity.name }}
            </div>
            <div class="small text-muted text">{{ entry.description }}</div>
        </div>
        <div class="pl-3">
            <h4 class="m-0">{{ entry.duration }}</h4>
        </div>
        <div class="pl-3" v-if="loading" >
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="pl-3" v-else @click="playPause">
            <CIcon
                v-if="entry.is_active"
                size="lg"
                class="align-top m-0 text-info"
                name="cid-media-pause-circle"
            />
            <CIcon
                v-else size="lg"
                class="align-top m-0" name="cid-media-play-circle" />
        </div>
    </div>
</template>
<script>

import {TimeEntryModel} from '@/models';

export default {
    name: 'TimeEntry',
    computed: {
        entry() {
            return this.entryData ? this.entryData : this.entryProp
        }
    },
    components: {},
    data() {
        return {
            loading: false,
            entryData: null
        }
    },
    props: {
        entryProp: {
            default: TimeEntryModel,
            type: Object
        }
    },
    methods: {
        playPause() {
            this.loading = true;
            axios.get(`time-entries/pause-play/${this.entry.id}`);
        }
    }
}
</script>
