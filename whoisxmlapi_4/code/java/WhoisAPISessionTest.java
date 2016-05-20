import java.io.IOException;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.http.Header;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.HttpResponseException;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;

/**
 *
 * @author Whois API
 */
public class WhoisAPISessionTest {
        public static void main(String[]args){
            boolean sessionURLRewrite=true; //false for suppport session via cookie
            String API_URL="http://www.whoisxmlapi.com/whoisserver/WhoisService";
            String[] domainNames={"bestwhoisservice.com", "whoisxmlapi.com", "test.com"};
            String username=null, password=null;
            final String[] sessionId=new String[1];//"4F4075C15ED6966EFBFC8C60D462A0D4";
            for(int i=0;i<domainNames.length;i++){
                if(i==0){
                    username="xxxx";
                    password="xxxx";
                }
                else{
                    username=null;
                    password=null;
                }
                String url = API_URL ;
                if(sessionId[0]!=null){
                     if(sessionURLRewrite)url+=";jsessionid="+sessionId[0]+"";
                 }
                url+="?domainName="+domainNames[i];
                if(username!=null)url+="&userName="+username+"&password="+password;

                HttpClient httpclient =null;
                try {
                    httpclient = new DefaultHttpClient();
                    HttpGet httpget = new HttpGet(url);
                    System.out.println("executing request " + httpget.getURI());

                    if(sessionId[0]!=null && !sessionURLRewrite){
                         httpget.setHeader("Cookie", "JSESSIONID="+sessionId[0]);
                    }
                    // Create a response handler
                    ResponseHandler<String> responseHandler = new BasicResponseHandler(){
                        public String handleResponse(HttpResponse response) throws HttpResponseException, IOException {
                            String res=super.handleResponse(response);

                            Header[] headers=response.getAllHeaders();

                            for(int i=0;i<headers.length;i++){
                                System.out.println(headers[i].getName()+","+headers[i].getValue());
                                if(headers[i].getName().equalsIgnoreCase("SET-COOKIE")){
                                    String cookieVal=headers[i].getValue();
                                    sessionId[0]=cookieVal.substring("JSESSIONID=".length());
                                }
                                else if(headers[i].getName().equalsIgnoreCase("SESSIONID")){
                                    sessionId[0]=headers[i].getValue();
                                }
                            }
                            return res;
                        }
                    };
                    String responseBody = httpclient.execute(httpget, responseHandler);
                    System.out.println(responseBody);
                    System.out.println("----------------------------------------");



                } catch (IOException ex) {
                	ex.printStackTrace();
                } finally{
                    if(httpclient!=null)httpclient.getConnectionManager().shutdown();
                }
            }
        }
}
