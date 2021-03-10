<template>
    <CDropdown
        :show="true"
        placement="bottom-end"
        :caret="false"
        in-nav
        class="c-header-nav-item mx-2 dropdown-wide"
        add-menu-classes="p-0 rounded"
    >
        <template #toggler>
            <CHeaderNavLink class="p-2 rounded">
                <CIcon class="m-0" name="cil-alarm"/>
            </CHeaderNavLink>
        </template>
        <CDropdownHeader
            tag="div"
            class="text-center bg-light"
        >
            <strong>Time Tracking</strong>
        </CDropdownHeader>
        <Stopwatch/>
        <div class="p-3 border-top" v-for="entry in timeEntries" >
            <TimeEntry :entry-prop="entry" :key="entry.id"/>
        </div>
    </CDropdown>
</template>
<script>

import axios from "axios";
import Stopwatch from '@/components/timetracking/Stopwatch'
import TimeEntry from '@/components/timetracking/TimeEntry'
import moment from "moment";

export default {
    name: 'DropdownTimetracking',
    components: {
        Stopwatch,
        TimeEntry
    },
    data() {
        return {
            timeEntriesData: []
        }
    },
    computed: {
        timeEntries() {
            return Object
                .keys(this.timeEntriesData)
                .map((key) => this.timeEntriesData[key])
                .sort((a, b) => {
                    if (a.is_active) {
                        return -1
                    } else if (moment(a.start).isAfter(b.start)) {
                        return -1
                    }
                    return 1
                })
        }
    },
    methods: {
        setTimeEntries: function () {
            let entries
            axios.getAll('/me/time-entries').then(res => {
                this.timeEntriesData = entries = res.data;
            });
        }
    },
    mounted() {
        console.log(this.$store.state)
        Echo.channel(`my-timers-${this.entryProp.id}`)
            .listen('.Timer-Updated', res => {
                this.loading = false;
                this.entryData = res.timer;
            })
    }
}
</script>

<style lang="scss">
.c-icon {
    margin-right: 0.5rem;
}

.dropdown-menu {
    max-height: calc(100vh - 50px);
    overflow-y: scroll;
}
</style>
