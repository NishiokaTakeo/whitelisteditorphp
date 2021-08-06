import { Component, OnInit } from '@angular/core';
// import * as internal from 'stream';
import { HttpService } from './services/http.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})

export class AppComponent  implements OnInit {
	title = 'whitelisteditor';

	result: any[] = [];        
	keyword: string = '';
	errormessage: string = ''
	messageClass: any;
	ngOnInit(): void {
		// throw new Error('Method not implemented.');
  	}

	constructor(private hserv: HttpService)
	{

	}

	submit()
	{
		this.addAsNew(this.keyword);
	}

	addAsNew(key: string)
	{
		return this.hserv.add(key).subscribe(response => this.getList(this.keyword));
	}

	getList(key: string)
	{
		return this.hserv.list(key).subscribe(response => this.result = response);
	}

	handleKeyUp(e: any){
		
		this.keyword = this.keyword || '';

		// if(e.keyCode === 13)
		if(this.keyword.length > 2)
		{
			if ( this.check())
			{
				this.messageClass= ['success'];
				this.errormessage = 'Format valid';

			}
			else 
			{
				this.messageClass= ['error'];
				this.errormessage = 'Format is invalid. It should email or domain format.'
			}

			this.getList(this.keyword);
			
		}

		console.log("typed with " + this.keyword)
	 }

	 check(): boolean
	 {

		let formatAddress : RegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/
		let formatdomain: RegExp = /^[^@\s]+\.[^@\s]+$/
		
		if (
			formatAddress.test(this.keyword)
			||
			formatdomain.test(this.keyword)
			)
		{
			return true;
		}
		else
		{
			return false;
		}
	 }

	 delete(key: string, i: number) : void {
		
		if ( confirm(`Would you like to remove item (${key})`))
		{
			this.hserv.delete(key).subscribe(response => {
				return this.getList(this.keyword);
			});
		}
	 }
	  
	 trackByFn(index: any, item: any) {
		return index;
	 }

}
