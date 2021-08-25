import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';

@Injectable()
export class HttpService {
	//endPoint = 'http://whitelisteditor.local:5001/server/api/';
	endPoint = '/server/api/';

    constructor(private httpClient: HttpClient) {
    
    }

	add(key: string): Observable<string> {
		return this.httpClient.get<string>(this.endPoint + `/default.php?key=${key}&mode=add`)
		.pipe(
		//   retry(1),
		  catchError(this.httpError)
		)
	  }  

	  
	delete(key: string): Observable<string> {
		return this.httpClient.get<string>(this.endPoint + `/default.php?key=${key}&mode=delete`)
		.pipe(
		//   retry(1),
		  catchError(this.httpError)
		)
	  }  

	edit(key: string, entryKey: string): Observable<string> {
		return this.httpClient.get<string>(this.endPoint + `/default.php?key=${key}&mode=edit&keyword=${entryKey}`)
		.pipe(
		//   retry(1),
		  catchError(this.httpError)
		)
	  }  
	  
	 list(key: string): Observable<string[]> {
		return this.httpClient.get<string[]>(this.endPoint + `/list.php?key=${key}`)
		.pipe(
		//   retry(1),
		  catchError(this.httpError)
		)
	  }  



	  httpError(error:any) {
		let msg = '';
		if(error.error instanceof ErrorEvent) {
		  // client side error
		  msg = error.error.message;
		} else {
		  // server side error
		  msg = `Error Code: ${error.status}\nMessage: ${error.message}`;
		}
		console.log(msg);
		return throwError(msg);
	  }
    
}