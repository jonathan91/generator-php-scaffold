import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { CardService } from './card.service';
import { ClickEvent } from './click.event';
import { HttpResponse } from '@angular/common/http';

@Component({
  selector: 'app-card',
  templateUrl: 'card.component.html',
  styleUrls: ['./card.component.css']
})
export class CardComponent implements OnInit {

  @Input()
  title: string;

  @Input()
  category: string;

  @Input()
  type = 'server';

  @Input()
  value: any;

  @Input()
  url: string;
  
  @Input()
  link: string;

  @Input()
  showButton = true;

  @Input()
  entity: string;

  @Input()
  disableButtonEdit: false;

  @Input()
  disableButtonView: false;

  @Input()
  disableButtonDelete: false;

  @Output()
  click: EventEmitter<ClickEvent> = new EventEmitter<ClickEvent>();

  objectKeys = Object.keys;

  selectedRow: any;

  constructor(
    private cardService: CardService
  ) { }

  ngOnInit() {
    this.click.emit(new ClickEvent('', this.selectedRow));
    if (this.type === 'server') {
        this.cardService.query(this.url).subscribe(
          (response: {data: Array<Object>, total: number}) => {
            this.value = response.data;
        }
      );
    }
  }
  
  onClick(button: string, event: any, row: any) {
    this.selectedRow = row;
    this.click.emit(new ClickEvent(button, this.selectedRow));
    event.stopPropagation();
  }
}
