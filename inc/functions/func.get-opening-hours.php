<?php
  function get_opening_hours($option = 'all') {
    $today = (object) DT;

    $closed_date_start = json_decode( get_field('p_closed_date_start', 'option') );
    $closed_date_end   = json_decode( get_field('p_closed_date_end', 'option') );
    $start_date = new DateTime($closed_date_start[0]);
    $end_date   = new DateTime($closed_date_end[0]);
    $nice_date = (object) [
      'start_date' => $start_date->format('d.m.Y'),
      'start_time' => $closed_date_start[1],
      'end_date'   => $end_date->format('d.m.Y'),
      'end_time'   => $closed_date_end[1]
    ];
    $closed = [
      'start' => [
        'date' => $closed_date_start[0],
        'time' => $closed_date_start[1]
      ],
      'end' => [
        'date' => $closed_date_end[0],
        'time' => $closed_date_end[1]
      ]
    ];



    if ( $closed_override = get_field( 'o_park_closed', 'option') ) {

      $out = (object) [
        'status' => 'closed',
        'dates'  =>  [
          'start' => $closed['start']['date'],
          'end'   => $closed['end']['date']
        ],
        'times' => [
          'start' => $closed['start']['time'],
          'end'   => $closed['end']['time']
        ],
        'message' => get_field('o_closing_notice_short','option')
      ];

      return $out;

    } else {

      if (
        in_between($today->date, $closed['start']['date'], $closed['end']['date']) &&
        in_between($today->time, $closed['start']['time'], $closed['end']['time'])
      ) {

        $out = (object) [
          'status' => 'closed',
          'dates'  =>  [
            'start' => $closed['start']['date'],
            'end'   => $closed['end']['date']
          ],
          'times' => [
            'start' => $closed['start']['time'],
            'end'   => $closed['end']['time']
          ],
          'message' => 'Park ist vom '.$nice_date->start_date.' bis zum '.$nice_date->end_date.' geschlossen.'
        ];

        return $out;

      } else {

        if ( $option == 'current' ) {

          $ranges = get_field('oh_datetime','option');
          if ( count($ranges) > 0 ) {
            foreach ( $ranges as $r ) {
              if ( in_between($today->date, $r['oh_start_date'], $r['oh_end_date']) ) {
                $out = (object) [
                  'status' => 'opened',
                  'dates'  =>  [
                    'start' => $r['oh_start_date'],
                    'end'   => $r['oh_end_date']
                  ],
                  'times' => [
                    'start' => $r['oh_start_time'],
                    'end'   => $r['oh_end_time']
                  ],
                  'message' => $r['oh_notice']
                ];
              }
            }
            return $out;
          } else {
            return false;
          }

        } else if ( $option == 'all' ) {

          $ranges = get_field('oh_datetime','option');
          if ( $ranges && count($ranges) > 1 ) {
            foreach ( $ranges as $r ) {
              $out[] = (object) [
                'status' => 'opened',
                'dates'  =>  [
                  'start' => $r['oh_start_date'],
                  'end'   => $r['oh_end_date']
                ],
                'times' => [
                  'start' => $r['oh_start_time'],
                  'end'   => $r['oh_end_time']
                ],
                'message' => $r['oh_notice']
              ];
            }
          } else {
            $out = (object) [
              'status' => 'opened',
              'dates'  =>  [
                'start' => $ranges[0]['oh_start_date'],
                'end'   => $ranges[0]['oh_end_date']
              ],
              'times' => [
                'start' => $ranges[0]['oh_start_time'],
                'end'   => $ranges[0]['oh_end_time']
              ],
              'message' => $ranges[0]['oh_notice']
            ];
          }

          return $out;

        } else {
          return false;
        }

      }

    }

  }