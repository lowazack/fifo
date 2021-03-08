<template>
	<div>
		<div class="row">
			<div class="col-md-12 calendar">
		  		<vue-cal
		  		style="height: 90vh;"
		  		:events="events"
		  		:time-from="8 * 60"
		  		:time-to="19 * 60"
		  		:events-on-month-view="short" />
		  	</div>
		</div>
	</div>
</template>

<script>

import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

export default {
    name: 'schedule',
    components: {
      VueCal
    },
    data: () => ({
    	splits: [{ label: 'Ryan', class: 'ryan' }, { label: 'Helen', class: 'helen' }, { label: 'Chris', class: 'chris' }],
  		editable: { title: false, drag: true, resize: true, create: true, delete: true },
  		selectedDate: new Date(),
    	events: [
	      {
	        start: '2021-01-02 10:35',
	        end: '2021-01-02 11:30',
	        title: 'Doctor appointment'
	      },
	      {
	        start: '2021-01-02 18:30',
	        end: '2021-01-02 19:15',
	        title: 'Dentist appointment'
	      },
	      {
	        start: '2021-01-03 18:30',
	        end: '2021-01-03 20:30',
	        title: 'Crossfit'
	      },
	      {
	        start: '2021-01-03 11:00',
	        end: '2021-01-03 13:00',
	        title: 'Brunch with Jane'
	      },
	      {
	        start: '2021-01-01 19:30',
	        end: '2021-01-01 23:00',
	        title: 'Swimming lesson'
	      },
	      {
	        start: '2021-01-02 19:30',
	        end: '2021-01-02 23:00',
	        title: 'Swimming lesson'
	      },
	      {
	        start: "2021-01-19 12:00",
	        end: "2021-01-19 14:00",
	        title: "LUNCH",
	        class: "lunch",
	        background: true
	      },
	      {
	        start: "2021-01-20 12:00",
	        end: "2021-01-20 14:00",
	        title: "LUNCH",
	        class: "lunch",
	        background: true
	      }
	    ]
    }),
}
</script>