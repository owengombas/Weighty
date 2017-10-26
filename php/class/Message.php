<?php
    class Message {
        public function SetMessage($status = 0, $message = null, $data = null) {
            $this->Status = $status;
            $this->Message = $message;
            $this->Data = $data;
        }
        
        public function GetJSON() {
            return json_encode($this);
        }

        public function SetError($message) {
            $this->Status = 0;
            $this->Message = $message;
        }
        
        public function SetSuccess($message) {
            $this->Status = 1;
            $this->Message = $message;
        }

        // Show the message depends on the status (1+: Success | 0-: Error)
        public function Show() {
            if(isset($this->Message)) {
                echo
                '<div class="alert alert-', $this->Status >= 1 ? 'success' : 'danger',' alert-dismissible fade show" role="alert">
                    ', $this->Message,
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }